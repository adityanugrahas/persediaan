<?php
session_start();
include("../global/koneksi.php");

if ($_SESSION["levelp"] === "su") {
    $id = $_REQUEST["id"];
    $br = db_fetch($bp, "SELECT * FROM stok_barang WHERE id_barang = :id", ['id' => $id]);
    
    if (!$br) {
        echo "Data tidak ditemukan.";
        exit;
    }

    $barang = (!empty($br["gambar"]) && file_exists("../img/barang/" . $br["gambar"])) ? $br["gambar"] : "barang.png";
    $seksi = db_fetch($bp, "SELECT bagian FROM bagian WHERE id_seksi = :id", ['id' => $_SESSION['seksip']]);
?>
<div id="custom-content" class="modal-block modal-block">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class="btn btn-dark btn-sm modal-dismiss">X</button>
            </div>
            <h2 class="card-title">Penambahan Stok Barang</h2>
            <span class='help-block'>Formulir Penambahan Stok Barang Persediaan</span>
        </header>
        <div class="card-body">
            <center>
                <span class="highlight" style='font-weight:bolder'><?= htmlspecialchars($br['nama_barang']) ?></span><br>
                <img src="img/barang/<?= htmlspecialchars($barang) ?>" height="200" alt="Barang Photo">
                <br>
                <span class="highlight">Jumlah Stok : <?= htmlspecialchars($br['jumlah_stok']) ?></span>
            </center> 
            <hr>
            <form id='stok_add_form' method='POST' action='?p=proadd&tab=stok_add' enctype='multipart/form-data' class='form-horizontal mb-lg'>
                <input type='hidden' name='jenis' value='in'>
                <input type='hidden' name='status' value='0'>	
                <input type='hidden' name='id_barang' value='<?= htmlspecialchars($br['id_barang']) ?>'>
                <input type='hidden' name='jum_stok' value='<?= htmlspecialchars($br['jumlah_stok']) ?>'>
                
                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Deskripsi Barang</label>
                    <div class="col-lg-8">
                        <input type='text' value="<?= htmlspecialchars($br['keterangan']) ?>" class='form-control' disabled>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Jumlah Penambahan</label>
                    <div class="col-lg-3">
                        <input type='number' name='jml_in' min='1' class='form-control' placeholder='Jumlah' required/>
                    </div>
                    <div class="col-lg-5">
                        <input type='text' class='form-control' value="<?= htmlspecialchars($br['satuan']) ?>" disabled/>
                        <input type='hidden' name='satuan' value="<?= htmlspecialchars($br['satuan']) ?>" />
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Harga Satuan</label>
                    <div class="col-lg-8">
                        <input type='number' name='harga' class='form-control' placeholder="Harga Satuan" value="<?= htmlspecialchars($br['harga_satuan']) ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Keterangan</label>
                    <div class="col-lg-8">
                        <input type='text' name='keterangan' class='form-control' placeholder="Keterangan penambahan">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Bagian / Seksi</label>
                    <div class="col-lg-8">
                        <input type='text' value="<?= htmlspecialchars($seksi['bagian'] ?? '') ?>" class='form-control' disabled>
                        <input type='hidden' name='id_seksi' value="<?= htmlspecialchars($_SESSION['seksip']) ?>">
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
                            <button type="submit" class='btn btn-primary modal-submit'><i class="fa fa-plus"></i> OK </button>
                        </div>
                </footer>	
            </form>
        </div>
    </section>
</div>
<?php
} else {
    header("location: ../index.php");
}
?>