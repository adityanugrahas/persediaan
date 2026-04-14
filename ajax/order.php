<?php
session_start();
require_once("../global/koneksi.php");
require_once("../global/ajax_header.php");

$id = $_REQUEST["id"];
$br = db_fetch($bp, "SELECT * FROM stok_barang WHERE id_barang = :id", ['id' => $id]);

if (!$br) {
    echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
    require_once("../global/ajax_header.php");
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
<div id="custom-content" class="modal-block modal-block-md">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class="btn btn-dark btn-sm modal-dismiss" title="Close">X</button>
            </div>
            <h2 class="card-title">Form <?= htmlspecialchars($judul) ?></h2>
            <p class='text-muted small mb-0'><?= htmlspecialchars($sub) ?></p>
        </header>
        <div class="card-body">
            <!-- Product Identity Row -->
            <div class="row align-items-center mb-4">
                <div class="col-md-4 text-center">
                    <img src="img/barang/<?= htmlspecialchars($barang) ?>" 
                         onerror="this.src='img/barang/barang.png'"
                         style="max-width: 100%; height: 120px; object-fit: contain; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.4));" 
                         alt="Item">
                </div>
                <div class="col-md-8">
                    <h3 class="mt-0 font-weight-bold" style="letter-spacing:-0.02em;"><?= htmlspecialchars($br['nama_barang']) ?></h3>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge badge-<?= $warna ?> mr-2" style="font-size: 0.8rem; padding: 5px 15px; border-radius: 20px;">
                            <i class="fas fa-layer-group mr-1"></i> <?= htmlspecialchars($br['jumlah_stok']) ?> <?= htmlspecialchars($br['satuan']) ?> TERSEDIA
                        </span>
                        <span class="text-muted small">Current Availability</span>
                    </div>
                    <p class="text-muted small mb-0"><i class="fas fa-info-circle mr-1"></i> <?= htmlspecialchars($br['keterangan'] ?: 'No description available') ?></p>
                </div>
            </div>

            <hr style="border-top: 1px solid var(--glass-border); margin: 25px 0;">

            <form id='order_form' method='POST' action='?p=proadd&tab=<?= htmlspecialchars($act) ?>' enctype='multipart/form-data' class='form-horizontal mb-lg'>
                <input type='hidden' name='jenis' value='out'>
                <input type='hidden' name='status' value='0'>	
                <input type='hidden' name='id_barang' value='<?= htmlspecialchars($br['id_barang']) ?>'>
                <input type='hidden' name='jum_stok' value='<?= htmlspecialchars($br['jumlah_stok']) ?>'>
                
                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2" style="letter-spacing:0.05em;">Jumlah Permintaan</label>
                    <div class="input-group">
                        <input type='number' name='jum_req' max='<?= htmlspecialchars($br['jumlah_stok']) ?>' min='1' class='form-control form-control-lg' placeholder='0' required style="border-radius: 12px 0 0 12px !important; font-weight:700;" />
                        <div class="input-group-append">
                            <span class="input-group-text px-4" style="border-radius: 0 12px 12px 0 !important; font-weight:700; background: var(--glass-medium);">
                                <?= htmlspecialchars($br['satuan']) ?>
                            </span>
                        </div>
                    </div>
                    <input type='hidden' name='satuan' value="<?= htmlspecialchars($br['satuan']) ?>" />
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2" style="letter-spacing:0.05em;">Keperluan / Keterangan</label>
                    <textarea name='keterangan' class='form-control' rows="2" placeholder="Tuliskan tujuan penggunaan barang..." style="border-radius: 12px !important;"></textarea>
                </div>
                
                <div class="row mt-4 pt-3" style="background: rgba(0,0,0,0.2); margin: 0 -30px -30px -30px; padding: 25px 30px; border-top: 1px solid var(--glass-border);">
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-circle text-primary mr-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <p class="mb-0 small text-white font-weight-bold"><?= htmlspecialchars($_SESSION['namap'] ?? 'Unknown User') ?></p>
                                <p class="mb-0 text-muted xs-text" style="font-size: 0.65rem; text-transform:uppercase; letter-spacing:0.05em;"><?= htmlspecialchars($seksi['bagian'] ?? 'General') ?></p>
                            </div>
                        </div>
                        <input type='hidden' name='id_seksi' value="<?= htmlspecialchars($_SESSION['seksip'] ?? '') ?>">
                        <input type='hidden' name='id_petugas' value="<?= htmlspecialchars($_SESSION['idp'] ?? '') ?>">
                    </div>
                    <div class="col-6 text-right">
                        <button type="button" class='btn btn-dark modal-dismiss px-4 mr-2' style="border-radius: 30px !important;">Batal</button>
                        <button type="submit" class='btn btn-primary px-5' style="border-radius: 30px !important; box-shadow: 0 10px 20px var(--primary-glow);">
                            <i class="fas fa-paper-plane mr-2"></i> Ajukan
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