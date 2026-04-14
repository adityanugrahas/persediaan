<?php
/**
 * Persediaan: Production Admin Seeder
 * Ensures a default admin user exists with username 'admin' and password 'admin'
 */

require_once __DIR__ . '/../global/koneksi.php';

$username = 'admin';
$password = 'admin';
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

try {
    // Check if user exists
    $stmt = $bp->prepare("SELECT COUNT(*) FROM petugas WHERE users_id = :user");
    $stmt->execute(['user' => $username]);
    $exists = (int)$stmt->fetchColumn();

    if ($exists > 0) {
        echo "🔄 Admin user already exists. Resetting password to 'admin'...\n";
        $bp->prepare("UPDATE petugas SET pwd = :pwd WHERE users_id = :user")
           ->execute(['pwd' => $hashed_password, 'user' => $username]);
    } else {
        echo "✨ Creating default admin user (admin/admin)...\n";
        $id_ptg = date('YmdHis');
        $bp->prepare("INSERT INTO petugas (id_petugas, users_id, pwd, nama, p_level, p_status) 
                      VALUES (:id, :user, :pwd, 'Administrator', 'su', 'aktif')")
           ->execute([
               'id' => $id_ptg,
               'user' => $username,
               'pwd' => $hashed_password
           ]);
    }
    echo "✅ Admin seeder completed.\n";
} catch (Exception $e) {
    die("❌ Seeding failed: " . $e->getMessage() . "\n");
}
