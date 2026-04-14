<?php
session_start();
include("../global/koneksi.php");

$id = $_REQUEST["id"] ?? '';
$bm = db_fetch($bp, "SELECT b.*, k.nama_kat, k.id_kat FROM bmn b JOIN kat_bmn k ON b.kat_bmn = k.id_kat WHERE b.id_bmn = :id", ['id' => $id]);

if (!$bm) {
    echo "Data BMN tidak ditemukan.";
    exit;
}
?>
<div id="custom-content" class="modal-block modal-block-full">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class='btn btn-dark btn-sm modal-dismiss'>X</button>
            </div>
            <h2 class="card-title">Form Edit Data BMN</h2>
        </header>
        <div class="card-body">
            <form id='bmn_edit_form' method='post' action='?p=update&tab=bmn' enctype='multipart/form-data' class='form-horizontal'>
                <div class="row">
                    <div class="col-md-6 border-right">
                        <input type="hidden" name="editor" value="<?= htmlspecialchars($_SESSION['idp']) ?>" />	
                        <input type="hidden" name="id_bmn" value="<?= htmlspecialchars($bm['id_bmn']) ?>" />
                        <input type="hidden" name="gbr_lama" value="<?= htmlspecialchars($bm['gambar']) ?>" />
                        
                        <div class="form-group row mb-3">
                            <div class="col-sm-12 text-center">
                                <?php $gambar = (!empty($bm["gambar"]) && file_exists("../img/barang/" . $bm["gambar"])) ? $bm["gambar"] : "barang.png"; ?>
                                <img src='img/barang/<?= htmlspecialchars($gambar) ?>' class="img-fluid rounded shadow-sm" style="max-height: 250px;">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2">Kategori</label>
                            <div class="col-lg-9">
                                <select name='kat_bmn' class='form-control' required>		
                                    <option value='<?= htmlspecialchars($bm['id_kat']) ?>'><?= htmlspecialchars($bm['nama_kat']) ?></option>
                                    <?php
                                    $kats = db_fetch_all($bp, "SELECT id_kat, nama_kat FROM kat_bmn WHERE status='ON' ORDER BY nama_kat");
                                    foreach ($kats as $kat) {
                                        echo "<option value='" . htmlspecialchars($kat['id_kat']) . "'>" . htmlspecialchars($kat['nama_kat']) . "</option>"; 
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>											  
                        
                        <div class="form-group row">
                            <label class="col-sm-3 control-label text-lg-right pt-2">Kode Barang</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kode" value='<?= htmlspecialchars($bm['kode_bmn']) ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 control-label text-lg-right pt-2">Nama Jenis Barang</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="nama_barang" value='<?= htmlspecialchars($bm['nama_bmn']) ?>' required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 control-label text-lg-right pt-2">Jumlah</label>
                            <div class="col-sm-4">
                                <input type="number" min='0' class="form-control" name="jumlah" value='<?= $bm['jumlah_bmn'] ?>'>
                            </div>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="satuan" value='<?= htmlspecialchars($bm['satuan']) ?>' placeholder="Satuan: Pcs, Unit, dll" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2">Asal Perolehan</label>	
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="asal_oleh" value='<?= htmlspecialchars($bm['asal_oleh'] ?? '') ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2">Tanggal Perolehan</label>	
                            <div class="col-sm-5">
                                <input type="date" class="form-control" name="tgl_oleh" value='<?= htmlspecialchars($bm['tgl_oleh'] ?? '') ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2">Bukti Perolehan</label>	
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="bukti_oleh" value='<?= htmlspecialchars($bm['bukti_oleh'] ?? '') ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2">Harga Perolehan</label>	
                            <div class="col-sm-8">
                                <input type="number" class="form-control" name="harga_oleh" value='<?= $bm['harga_oleh'] ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2">Keterangan</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="keterangan" rows="3"><?= htmlspecialchars($bm['keterangan']) ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2">Lampiran</label>
                            <div class="col-sm-8">
                                <input type="file" class="form-control" name="lampiran">
                                <span class="help-block">Abaikan jika tidak ganti lampiran.</span>
                            </div>
                        </div>  
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2"></label>
                            <div class="col-sm-8 text-right pt-2">
                                <button type="button" class='btn btn-default modal-dismiss'>Batal</button>
                                <input type="reset" class="btn btn-warning" value="Reset">
                                <button type="submit" class="btn btn-primary">Update Data BMN</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>