<?php
/**
 * Login Process — Authenticates users
 * Uses password_safe_verify for bcrypt/plaintext dual-mode
 */
session_start();
require_once '../global/koneksi.php';

// Get form data
$nama    = trim($_POST['nama'] ?? '');
$user_id = strip_tags(trim($_POST['user_id'] ?? ''));
$pwd     = strip_tags(trim($_POST['pwd'] ?? ''));
$id      = date("YmdHis");

// Check if any petugas exists
$stmt_check = $bp->query("SELECT id_petugas FROM petugas LIMIT 1");
$petugas_exists = $stmt_check->fetch();

if ($petugas_exists) {
    // Authenticate user — fetch by user_id only, then verify password in PHP
    $stmt = $bp->prepare("SELECT * FROM petugas WHERE users_id = :user_id AND p_status = 'aktif'");
    $stmt->execute(['user_id' => $user_id]);
    $row = $stmt->fetch();

    if ($row && password_safe_verify($pwd, $row['pwd'])) {
        $intime = date("Y-m-d H:i:s");

        // If password is still plaintext, upgrade to bcrypt silently
        if (!str_starts_with($row['pwd'], '$2y$') && !str_starts_with($row['pwd'], '$2b$')) {
            $hashed = password_safe_hash($pwd);
            $bp->prepare("UPDATE petugas SET pwd = :pwd WHERE id_petugas = :id")
               ->execute(['pwd' => $hashed, 'id' => $row['id_petugas']]);
        }

        // Update last login
        $stmt_update = $bp->prepare("UPDATE petugas SET last_in = :last_in WHERE id_petugas = :id");
        $stmt_update->execute(['last_in' => $intime, 'id' => $row['id_petugas']]);

        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);

        // Set session data
        $_SESSION['idp']      = $row['id_petugas'];
        $_SESSION['statep']   = "login";
        $_SESSION['user_idp'] = $row['users_id'];
        $_SESSION['namap']    = $row['nama'];
        $_SESSION['pwdp']     = '***'; // Don't store password in session
        $_SESSION['photop']   = !empty($row['photo']) ? $row['photo'] : "user.png";
        $_SESSION['levelp']   = $row['p_level'];
        $_SESSION['seksip']   = $row['seksi'];
        $_SESSION['statusp']  = $row['p_status'];

        // Redirect based on level
        switch ($_SESSION['levelp']) {
            case 'su':
                header("location:../su.php?pesan=welcome");
                break;
            case 'adm':
                header("location:../adm.php?pesan=welcome");
                break;
            case 'opr':
                header("location:../opr.php?pesan=welcome");
                break;
            default:
                header("location:../index.php?pesan=gagal");
        }
        exit;
    } else {
        header("location:../index.php?pesan=gagal");
        exit;
    }
} else {
    // Initial setup: create first admin (su level) with hashed password
    $hashed_pwd = password_safe_hash($pwd);

    $stmt_insert = $bp->prepare("INSERT INTO petugas (id_petugas, users_id, pwd, nama, seksi, jabatan, p_level, photo, ttd, last_in, last_out, urutan, p_status)
                                 VALUES (:id, :user_id, :pwd, :nama, '', '', 'su', '', '', '', '', 0, 'aktif')");
    $result = $stmt_insert->execute([
        'id'      => $id,
        'user_id' => $user_id,
        'pwd'     => $hashed_pwd,
        'nama'    => $nama,
    ]);

    if ($result) {
        header("location:../index.php?add=ok");
    } else {
        header("location:../index.php?add=fail");
    }
    exit;
}
?>
