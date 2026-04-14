<?php
/**
 * Logout Process — Session cleanup
 * Migrated to PDO
 */
require_once 'global/koneksi.php';
session_start();

$now = date("Y-m-d H:i:s");

if (!empty($_SESSION['idp'])) {
    $bp->prepare("UPDATE petugas SET last_out = :now WHERE id_petugas = :id")
       ->execute(['now' => $now, 'id' => $_SESSION['idp']]);
}

session_destroy();
header("Location: index.php?pesan=logout");
exit;
?>
