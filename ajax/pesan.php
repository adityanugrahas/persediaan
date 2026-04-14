<?php
session_start();
include("../global/koneksi.php");
require_once("../global/ajax_header.php");

$id = $_REQUEST["id"];
$br = db_fetch($bp, "SELECT * FROM stok_barang WHERE id_barang = :id", ['id' => $id]);

if (!$br) {
    echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
    require_once("../global/ajax_footer.php");
    exit;
}

$barang = (!empty($br["gambar"]) && file_exists("../img/barang/" . $br["gambar"])) ? $br["gambar"] : "barang.png";

if ($br["jumlah_stok"] > 0) { 
    $judul = "Permohonan";
    $sub = "Logistical Disbursement Request";
    $warna = "primary";
    $act = "order";
} else { 
    $judul = "Pesanan";
    $warna = "warning";
    $sub = "Inventory Requisition for Out-of-Stock Item";
    $act = "pesan";
} 

$seksi = db_fetch($bp, "SELECT bagian FROM bagian WHERE id_seksi = :id", ['id' => $_SESSION['seksip']]);
?>

<div id="modalHeaderColorDanger" class="modal-block modal-block-md">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class="btn btn-dark btn-sm modal-dismiss" title="Close"><i class="fa fa-times"></i></button>
            </div>
            <h2 class="card-title"><i class="fa fa-shopping-cart mr-2"></i> Form <?= htmlspecialchars($judul) ?></h2>
            <p class="mb-0 text-muted small" style="opacity: 0.8;"><?= htmlspecialchars($sub) ?></p>
        </header>
        <div class="card-body">
            <div class="row mb-4 align-items-center">
                <div class="col-md-4 text-center">
                    <div style="background: var(--glass-heavy); border-radius: var(--radius-md); padding: 10px; border: 1px solid var(--glass-border);">
                        <img src="img/barang/<?= htmlspecialchars($barang) ?>" style="max-width: 100%; height: 120px; object-fit: contain; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.5));" alt="Barang Photo" onerror="this.src='img/barang/barang.png'">
                    </div>
                </div>
                <div class="col-md-8">
                    <h3 class="mt-0 text-white font-weight-bold" style="letter-spacing:-0.03em;"><?= htmlspecialchars($br['nama_barang']) ?></h3>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge badge-<?= $warna ?> mr-2" style="font-size: 0.8rem; padding: 5px 15px; border-radius: 20px;">
                            STOK: <?= htmlspecialchars($br['jumlah_stok']) ?> <?= htmlspecialchars($br['satuan']) ?>
                        </span>
                    </div>
                    <p class="text-muted small mb-0"><i class="fa fa-info-circle mr-1"></i> <?= htmlspecialchars($br['keterangan'] ?: 'No description provided') ?></p>
                </div>
            </div>
            
            <hr style="border-top: 1px solid var(--glass-border); margin: 25px 0;">
            
            <form id='order_form' method='POST' action='?p=proadd&tab=<?= htmlspecialchars($act) ?>' enctype='multipart/form-data' class='form-horizontal'>
                <input type='hidden' name='jenis' value='out'>
                <input type='hidden' name='status' value='0'>	
                <input type='hidden' name='id_barang' value='<?= htmlspecialchars($br['id_barang']) ?>'>
                <input type='hidden' name='nama_barang' value='<?= htmlspecialchars($br['nama_barang']) ?>'>
                <input type='hidden' name='ket_barang' value='<?= htmlspecialchars($br['keterangan']) ?>'>
                <input type='hidden' name='jum_stok' value='<?= htmlspecialchars($br['jumlah_stok']) ?>'>
                
                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2" style="letter-spacing:0.05em;">Jumlah <?= htmlspecialchars($judul) ?></label>
                    <div class="input-group">
                        <input type='number' name='jum_pes' min='1' max='<?= $br['jumlah_stok'] > 0 ? $br['jumlah_stok'] : "" ?>' class='form-control form-control-lg' placeholder='0' required style="border-radius: 12px 0 0 12px !important; font-weight:700;" />
                        <div class="input-group-append">
                            <span class="input-group-text px-4" style="border-radius: 0 12px 12px 0 !important; font-weight:700; background: var(--glass-medium);">
                                <?= htmlspecialchars($br['satuan']) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2" style="letter-spacing:0.05em;">Keterangan / Keperluan</label>
                    <textarea name='keterangan' class='form-control' rows="2" placeholder="Tuliskan alasan pemesanan atau catatan pemakaian..." style="border-radius: 12px !important;"></textarea>
                </div>

                <div class="row mt-4 pt-3" style="background: rgba(0,0,0,0.2); margin: 0 -30px -30px -30px; padding: 25px 30px; border-top: 1px solid var(--glass-border); border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-id-badge text-primary mr-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <p class="mb-0 small text-white font-weight-bold"><?= htmlspecialchars($_SESSION['namap']) ?></p>
                                <p class="mb-0 text-muted xs-text" style="font-size: 0.65rem; text-transform:uppercase; letter-spacing:0.05em;"><?= htmlspecialchars($seksi['bagian'] ?? 'GENERAL') ?></p>
                            </div>
                        </div>
                        <input type='hidden' name='id_seksi' value="<?= htmlspecialchars($_SESSION['seksip']) ?>">
                        <input type='hidden' name='id_petugas' value="<?= htmlspecialchars($_SESSION['idp']) ?>">
                    </div>
                    <div class="col-6 text-right">
                        <button type="button" class='btn btn-dark modal-dismiss px-4 mr-2' style="border-radius: 30px !important;">Batal</button>
                        <button type="submit" class='btn btn-primary px-5 modal-submit' style="border-radius: 30px !important; box-shadow: 0 10px 20px var(--primary-glow);">
                            <i class="fa fa-check-circle mr-2"></i> Konfirmasi
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