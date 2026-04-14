<?php
include("global/koneksi.php");
try {
    $bp->exec("ALTER TABLE setting ADD COLUMN favicon TEXT DEFAULT 'favicon.png'");
    echo "Column favicon added successfully.";
} catch (PDOException $e) {
    echo "Column might already exist: " . $e->getMessage();
}
?>
