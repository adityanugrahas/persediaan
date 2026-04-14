<?php
require_once "global/koneksi.php";
$stmt = $bp->query("SELECT users_id, nama, level FROM petugas LIMIT 5");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($users);
?>
