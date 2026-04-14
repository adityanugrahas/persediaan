<?php
session_start();
if ($_SESSION["levelp"] == "su") {
    include("../global/koneksi.php");
    $id = $_REQUEST["id"] ?? '';
    $data = db_fetch($bp, "SELECT * FROM stok_barang WHERE id_barang = :id", ['id' => $id]);
    
    if (!$data) {
        echo "Data tidak ditemukan.";
        exit;
    }
?>
    <div id='custom-content' class='modal-block modal-full-color modal-block-danger'>
        <section class='card'>
            <header class='card-header'>
                <h2 class='card-title'>Konfirmasi Penghapusan</h2>
            </header>
            <div class='card-body'>
                <div class='modal-wrapper'>
                    <div class='modal-icon'>
                        <i class='fa fa-times-circle'></i>
                    </div>
                    <div class='modal-text'>
                        <p>Apakah Anda yakin ingin menghapus data barang berikut?</p>
                        <h4 class="font-weight-bold"><?= htmlspecialchars($data['nama_barang']) ?></h4>
                        <p class="small text-white-50">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                </div>
            </div>
            <footer class='card-footer'>
                <div class='row'>
                    <div class='col-md-12 text-right'>
                        <a href='?p=delete&tab=stok&id=<?= htmlspecialchars($data['id_barang']) ?>' class='btn btn-dark'><i class='fa fa-trash'></i> Ya, Hapus</a>
                        <button class='btn btn-outline-light modal-dismiss'>Batal</button>
                    </div>
                </div>
            </footer>
        </section>
    </div>
<?php
} else {
?>
    <script>
        window.location = "index.php";
    </script>
<?php 
} ?>