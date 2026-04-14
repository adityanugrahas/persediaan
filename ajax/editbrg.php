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
?>
<div id="custom-content" class="modal-block modal-block">
    <section class="card">
        <header class="card-header">
            <h2 class="card-title">Form Edit Data Barang</h2>
        </header>
        <div class="card-body">
            <form id='edit_barang_form' method='post' action='?p=update&tab=barang' enctype='multipart/form-data' class='form-horizontal mb-lg'>
                <input type="hidden" name="editor" value="<?= htmlspecialchars($_SESSION['idp']) ?>" />	
                <input type="hidden" name="id_barang" value="<?= htmlspecialchars($br['id_barang']) ?>" />
                
                <div class="form-group row">
                    <label class="col-lg-3 control-label text-lg-right pt-2">Kategori</label>
                    <div class="col-sm-5">
                        <select name='kategori' class='form-control' required>		
                            <?php
                            $skat = db_fetch_all($bp, "SELECT id_kat, nama_kat FROM kategori WHERE status='ON' ORDER BY nama_kat");
                            foreach ($skat as $kat) {
                                $selected = ($kat['id_kat'] == $br['kategori']) ? "selected" : "";
                                echo "<option value='" . htmlspecialchars($kat['id_kat']) . "' $selected>" . htmlspecialchars($kat['nama_kat']) . "</option>"; 
                            }
                            ?>
                        </select>
                    </div>
                    <div class='col-lg-4'><a href='?p=kat_add' class='btn btn-info'><i class='fa fa-plus'></i> Tambah Kategori </a></div>
                </div>											  
                <div class="form-group row">
                    <label class="col-sm-3 control-label text-lg-right pt-2">Nama Jenis Barang</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="nama_barang" value="<?= htmlspecialchars($br['nama_barang']) ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 control-label text-lg-right pt-2">Satuan</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="satuan" value="<?= htmlspecialchars($br['satuan']) ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 control-label text-lg-right pt-2">Stok Minimal</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" name="batas_stok" value="<?= htmlspecialchars($br['stok_minimal']) ?>" required>
                    </div>
                </div>	
                <div class="form-group row">
                    <label class="col-sm-3 control-label text-lg-right pt-2">Harga Satuan</label>	
                    <div class="col-sm-8">
                        <input type="number" class="form-control" name="harga" value="<?= htmlspecialchars($br['harga_satuan']) ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 control-label text-lg-right pt-2">Keterangan</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" name="keterangan" rows="3"><?= htmlspecialchars($br['keterangan']) ?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 control-label text-lg-right pt-2">Lampiran</label>
                    <div class="col-sm-8">
                        <img src="img/barang/<?= htmlspecialchars($barang) ?>" height="100" class="mb-2 d-block">
                        <input type="file" class="form-control" name="lampiran">
                        <span class="help-block">Abaikan jika tidak ganti gambar.</span>
                    </div>
                </div>  
                <div class="form-group row">
                    <label class="col-sm-3 control-label text-lg-right pt-2">Operator</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['namap']) ?>" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 control-label text-lg-right pt-2"></label>
                    <div class="col-sm-8 text-lg-right pt-2">
                        <button class='btn btn-default modal-dismiss'>Batal</button>
                        <input type="reset" class="btn btn-warning" value="Reset">
                        <input type="submit" class="btn btn-success" value=" Update ">
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
<?php
} else {
    header("location: ../index.php");
}
?>