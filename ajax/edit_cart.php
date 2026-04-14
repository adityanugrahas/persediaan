<?php
session_start();
include("../global/koneksi.php");

$id = $_REQUEST["id"];
$br = db_fetch($bp, "SELECT b.nama_barang, b.gambar, b.jumlah_stok, b.keterangan as ket_br, i.id_inout, i.id_barang, i.jml_in, i.jml_req, i.harga, i.satuan, i.keterangan, i.jenis 
                    FROM stok_inout i 
                    JOIN stok_barang b ON b.id_barang = i.id_barang 
                    WHERE i.id_inout = :id", ['id' => $id]);

if (!$br) {
    echo "Data tidak ditemukan.";
    exit;
}

$barang = (!empty($br["gambar"]) && file_exists("../img/barang/" . $br["gambar"])) ? $br["gambar"] : "barang.png";
$seksi = db_fetch($bp, "SELECT bagian FROM bagian WHERE id_seksi = :id", ['id' => $_SESSION['seksip']]);
$judul = ($br["jenis"] === "in") ? "Penambahan" : "Permintaan";
?>

<div id="custom-content" class="modal-block modal-block">
    <section class="card">
        <header class="card-header">
            <h2 class="card-title">Form <?= htmlspecialchars($judul) ?> Barang</h2>
        </header>
        <div class="card-body">
            <center>
                <span class="highlight" style='font-weight:bolder'><?= htmlspecialchars($br['nama_barang']) ?></span><br>
                <img src="img/barang/<?= htmlspecialchars($barang) ?>" height="200" alt="Barang Photo">
                <br>
                <span class="highlight">Jumlah Stok : <?= htmlspecialchars($br['jumlah_stok']) ?></span>
            </center> 
            <hr>
            <form id='cart_edit_form' method='POST' action='?p=update&tab=cart' enctype='multipart/form-data' class='form-horizontal mb-lg'>
                <input type="hidden" name="id_inout" value="<?= htmlspecialchars($br['id_inout']) ?>">
                <input type="hidden" name="jenis" value="<?= htmlspecialchars($br['jenis']) ?>">
                
                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Deskripsi Barang</label>
                    <div class="col-lg-8">
                        <input type='text' value="<?= htmlspecialchars($br['ket_br']) ?>" class='form-control' disabled>
                    </div>
                </div>

                <?php if ($br["jenis"] === "in"): ?>
                <div class='form-group row'>
                    <label class='col-lg-4 control-label text-lg-right pt-2'>Jumlah Penambahan</label>
                    <div class='col-lg-3'>
                        <input type='number' name='jml_in' value='<?= htmlspecialchars($br['jml_in']) ?>' class='form-control' placeholder='Jumlah' required/>
                    </div>
                    <div class='col-lg-5'>
                        <input type='text' class='form-control' value='<?= htmlspecialchars($br['satuan']) ?>' disabled/>
                        <input type='hidden' name='satuan' value='<?= htmlspecialchars($br['satuan']) ?>' />
                    </div>
                </div>

                <div class='form-group row'>
                    <label class='col-lg-4 control-label text-lg-right pt-2'>Harga Satuan</label>
                    <div class='col-lg-8'>
                        <input type='text' name='harga' value='<?= htmlspecialchars($br['harga']) ?>' class='form-control' placeholder='Harga'>
                    </div>
                </div>
                <?php else: ?>
                <div class='form-group row'>
                    <label class='col-lg-4 control-label text-lg-right pt-2'>Jumlah Permintaan</label>
                    <div class='col-lg-3'>
                        <input type='number' name='jml_req' value='<?= htmlspecialchars($br['jml_req']) ?>' class='form-control' placeholder='Jumlah' required/>
                    </div>
                    <div class='col-lg-5'>
                        <input type='text' class='form-control' value='<?= htmlspecialchars($br['satuan']) ?>' disabled/>
                        <input type='hidden' name='satuan' value='<?= htmlspecialchars($br['satuan']) ?>' />
                    </div>
                </div>
                <?php endif; ?>

                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Keterangan</label>
                    <div class="col-lg-8">
                        <input type='text' name='keterangan' class='form-control' placeholder="Keterangan" value="<?= htmlspecialchars($br['keterangan']) ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Petugas</label>
                    <div class="col-lg-8">
                        <input type='text' value="<?= htmlspecialchars($_SESSION['namap']) ?>" class='form-control' disabled>
                        <input type='hidden' name='id_petugas' value="<?= htmlspecialchars($_SESSION['idp']) ?>">
                    </div>
                </div>
                
                <hr>
                <footer class='panel-footer'>
                        <div class='col-md-12 text-right'>
                            <button type="button" class='btn btn-default modal-dismiss'>Batal</button>
                            <button type="submit" class='btn btn-primary modal-submit'><i class="fa fa-save"></i> OK </button>
                        </div>
                </footer>	
            </form>
        </div>
    </section>
</div>