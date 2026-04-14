<?php
session_start();
include("../global/koneksi.php");
require_once("../global/ajax_header.php");

$id = $_SESSION["seksip"] ?? '';
$seksi = db_fetch($bp, "SELECT bagian FROM bagian WHERE id_seksi = :id", ['id' => $id]);
?>

<div id="custom-content" class="modal-block modal-block-md">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class="btn btn-dark btn-sm modal-dismiss" title="Close">X</button>
            </div>
            <h2 class="card-title">Pemesanan Barang Baru</h2>
            <p class="text-muted small mb-0">Non-Catalog Acquisition Interface</p>
        </header>
        <div class="card-body">
            <form id='order_new_form' method='POST' action='?p=proadd&tab=pesan' enctype='multipart/form-data' class='form-horizontal'>
                <input type='hidden' name='jenis' value='out'>
                <input type='hidden' name='status' value='0'>	
                
                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2">Nama Barang</label>
                    <input type='text' name='nama_barang' class='form-control form-control-lg' placeholder='Tuliskan nama barang lengkap...' required style="border-radius:12px !important; font-weight:700;">
                </div>
                
                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2">Deskripsi / Spesifikasi</label>
                    <textarea name='ket_barang' class='form-control' rows="2" placeholder='Detail, merk, atau spek yang dibutuhkan...' required style="border-radius:12px !important;"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted small text-uppercase mb-2">Estimasi Jumlah</label>
                            <input type='number' name='jum_pes' min='1' class='form-control form-control-lg' placeholder='0' required style="border-radius:12px !important; font-weight:700;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted small text-uppercase mb-2">Satuan</label>
                            <input type='text' name="satuan" class='form-control form-control-lg' placeholder='Ex: Pcs, Rim, Box' required style="border-radius:12px !important; font-weight:700;">
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2">Tujuan Penggunaan</label>
                    <input type='text' name='keterangan' class='form-control' placeholder="Contoh: Untuk kebutuhan operasional Front Office" style="border-radius:12px !important;">
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2">Lampiran Pendukung (Foto/Spek)</label>
                    <div class="p-2" style="background: rgba(255,255,255,0.02); border: 2px dashed var(--glass-border); border-radius: 12px;">
                        <input type='file' name='lampiran' class='form-control-file'>
                    </div>
                </div>

                <div class="row mt-4 pt-4" style="background: rgba(0,0,0,0.2); margin: 0 -30px -30px -30px; padding: 25px 30px; border-top: 1px solid var(--glass-border); border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-building text-primary mr-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <p class="mb-0 small text-white font-weight-bold"><?= htmlspecialchars($seksi['bagian'] ?? 'GENERAL') ?></p>
                                <p class="mb-0 text-muted xs-text" style="font-size: 0.65rem; text-transform:uppercase; letter-spacing:0.05em;"><?= htmlspecialchars($_SESSION['namap'] ?? 'Staff') ?></p>
                            </div>
                        </div>
                        <input type='hidden' name='id_seksi' value="<?= htmlspecialchars($_SESSION['seksip'] ?? '') ?>">
                        <input type='hidden' name='id_petugas' value="<?= htmlspecialchars($_SESSION['idp'] ?? '') ?>">
                    </div>
                    <div class="col-6 text-right">
                        <button type="button" class='btn btn-dark modal-dismiss px-4 mr-2' style="border-radius: 30px !important;">Batal</button>
                        <button type="submit" class='btn btn-primary px-5 modal-submit' style="border-radius: 30px !important; box-shadow: 0 10px 20px var(--primary-glow);">
                            <i class="fas fa-shopping-basket mr-2"></i> Ajukan Pesanan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<?php 
require_once("../global/ajax_footer.php");
?>