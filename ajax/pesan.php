<?php
session_start();
include("../global/koneksi.php");

$id = $_REQUEST["id"];
$br = db_fetch($bp, "SELECT * FROM stok_barang WHERE id_barang = :id", ['id' => $id]);

if (!$br) {
    echo "Data tidak ditemukan.";
    exit;
}

$barang = (!empty($br["gambar"]) && file_exists("../img/barang/" . $br["gambar"])) ? $br["gambar"] : "barang.png";

if ($br["jumlah_stok"] > 0) { 
    $judul = "Permohonan";
    $sub = "Formulir Permohonan Pemakaian Barang Persediaan";
    $warna = "primary";
    $act = "order";
} else { 
    $judul = "Pesanan";
    $warna = "warning";
    $sub = "Formulir Pemesanan Barang Stok Tidak Tersedia";
    $act = "pesan";
} 

$seksi = db_fetch($bp, "SELECT bagian FROM bagian WHERE id_seksi = :id", ['id' => $_SESSION['seksip']]);
?>

<div id="modalHeaderColorDanger" class="modal-block modal-block-md">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class="btn btn-dark btn-sm modal-dismiss"><i class="fa fa-times"></i></button>
            </div>
            <h2 class="card-title"><i class="fa fa-shopping-cart mr-2"></i> Form <?= htmlspecialchars($judul) ?></h2>
            <p class="mb-0 text-muted" style="font-size: 0.85rem; opacity: 0.8;"><?= htmlspecialchars($sub) ?></p>
        </header>
        <div class="card-body">
            <div class="row mb-4 align-items-center">
                <div class="col-md-4 text-center">
                    <div style="background: var(--glass-heavy); border-radius: var(--radius-md); padding: 10px; border: 1px solid var(--glass-border);">
                        <img src="img/barang/<?= htmlspecialchars($barang) ?>" style="max-width: 100%; max-height: 180px; border-radius: var(--radius-sm);" alt="Barang Photo" onerror="this.src='img/barang/barang.png'">
                    </div>
                </div>
                <div class="col-md-8">
                    <h3 class="mt-0 text-white font-weight-bold"><?= htmlspecialchars($br['nama_barang']) ?></h3>
                    <div class="badge badge-<?= $warna ?> mb-2">Stok: <?= htmlspecialchars($br['jumlah_stok']) ?> <?= htmlspecialchars($br['satuan']) ?></div>
                    <p class="text-muted small mb-0"><i class="fa fa-info-circle"></i> <?= htmlspecialchars($br['keterangan']) ?></p>
                </div>
            </div>
            
            <hr class="border-secondary opacity-2">
            
            <form id='order_form' method='POST' action='?p=proadd&tab=<?= htmlspecialchars($act) ?>' enctype='multipart/form-data' class='form-horizontal'>
                <input type='hidden' name='jenis' value='out'>
                <input type='hidden' name='status' value='0'>	
                <input type='hidden' name='id_barang' value='<?= htmlspecialchars($br['id_barang']) ?>'>
                <input type='hidden' name='nama_barang' value='<?= htmlspecialchars($br['nama_barang']) ?>'>
                <input type='hidden' name='ket_barang' value='<?= htmlspecialchars($br['keterangan']) ?>'>
                <input type='hidden' name='jum_stok' value='<?= htmlspecialchars($br['jumlah_stok']) ?>'>
                
                <div class="form-group row mb-4">
                    <label class="col-lg-4 control-label text-white font-weight-semibold">Jumlah <?= htmlspecialchars($judul) ?></label>
                    <div class="col-lg-8">
                        <div class="input-group">
                            <input type='number' name='jum_pes' min='1' max='<?= $br['jumlah_stok'] > 0 ? $br['jumlah_stok'] : "" ?>' class='form-control' placeholder='Jumlah' required/>
                            <span class="input-group-append">
                                <span class="input-group-text"><?= htmlspecialchars($br['satuan']) ?></span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-4">
                    <label class="col-lg-4 control-label text-white font-weight-semibold">Keterangan / Keperluan</label>
                    <div class="col-lg-8">
                        <textarea name='keterangan' class='form-control' rows="2" placeholder="Tuliskan keterangan pemakaian..."></textarea>
                    </div>
                </div>

                <div class="form-group row mb-4">
                    <label class="col-lg-4 control-label text-white font-weight-semibold">Unit & Petugas</label>
                    <div class="col-lg-8">
                        <div class="p-3 border-radius-md" style="background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: var(--radius-sm);">
                            <div class="small text-muted mb-1">BAGIAN / SEKSI</div>
                            <div class="font-weight-bold text-white mb-2"><?= htmlspecialchars($seksi['bagian'] ?? 'N/A') ?></div>
                            <div class="small text-muted mb-1">NAMA PETUGAS</div>
                            <div class="font-weight-bold text-white"><?= htmlspecialchars($_SESSION['namap']) ?></div>
                        </div>
                        <input type='hidden' name='id_seksi' value="<?= htmlspecialchars($_SESSION['seksip']) ?>">
                        <input type='hidden' name='id_petugas' value="<?= htmlspecialchars($_SESSION['idp']) ?>">
                    </div>
                </div>
                
                <div class='mt-5 text-right'>
                    <button type="button" class='btn btn-dark px-4 mr-2 modal-dismiss'>Batal</button>
                    <button type="submit" class='btn btn-primary px-5 modal-submit'>
                        <i class="fa fa-check-circle mr-2"></i> Konfirmasi <?= htmlspecialchars($judul) ?>
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>