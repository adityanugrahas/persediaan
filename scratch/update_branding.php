<?php
require_once "global/koneksi.php";
$sql = "UPDATE setting SET logo_header = 'branding/logo_imigrasi.png', favicon = 'branding/logo_imigrasi.png' WHERE setting_id = '20210212131943'";
$bp->exec($sql);
echo "Branding updated successfully in DB.";
?>
