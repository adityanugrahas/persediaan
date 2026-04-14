<!-- start: page -->
<?php
$idkat_bmn = $_REQUEST["kat_bmn"] ?? '';
$kat_bmn_name = "-";

if (!empty($idkat_bmn)) {
    $skat = db_fetch($bp, "SELECT * FROM kat_bmn WHERE id_kat = :id", ['id' => $idkat_bmn]);
    if ($skat) {
        $kat_bmn_name = $skat["nama_kat"];
    }
}
?>
<div class="row">
    <div class="col col-lg-6" style="float:none;margin:auto;">
        <section class="card">
            <header class="card-header">
                <div class="card-actions">
                    <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                </div>
                <h2 class="card-title">Form Tambah Data BMN</h2>
            </header>
            <div class="card-body">
                <form id='add_bmn_form' method='post' action='?p=proadd&tab=bmn' enctype='multipart/form-data' class='form-horizontal mb-lg'>
                    <input type="hidden" name="editor" value="<?= htmlspecialchars($_SESSION['idp']) ?>" />	
                    <div class="form-group row">
                        <label class="col-lg-3 control-label text-lg-right pt-2">Kategori</label>
                        <div class="col-lg-4">
                            <select name='kat_bmn' class='form-control' required>		
                                <option value='<?= htmlspecialchars($idkat_bmn) ?>'><?= htmlspecialchars($kat_bmn_name) ?></option>
                                <?php
                                $kats = db_fetch_all($bp, "SELECT id_kat, nama_kat FROM kat_bmn WHERE status='ON' ORDER BY nama_kat");
                                foreach ($kats as $kat) {
                                    echo "<option value='" . htmlspecialchars($kat['id_kat']) . "'>" . htmlspecialchars($kat['nama_kat']) . "</option>"; 
                                }
                                ?>
                            </select>
                        </div>
                        <div class='col-lg-4'><a href='ajax/bmn_kat_add.php' class='simple-ajax-modal btn btn-success'><i class="fa fa-plus"></i> Kategori</a></div>
                    </div>											  
                    
                    <div class="form-group row">
                        <label class="col-sm-3 control-label text-lg-right pt-2">Kode Barang</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kode">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label text-lg-right pt-2">Nama Jenis Barang</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nama_barang" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label text-lg-right pt-2">Jumlah</label>
                        <div class="col-sm-4">
                            <input type="number" min='0' class="form-control" name="jumlah">
                        </div>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="satuan" placeholder="Satuan: Pcs, Unit, Pack, dll" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label text-lg-right pt-2">Asal Perolehan</label>	
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="asal_oleh">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label text-lg-right pt-2">Tanggal Perolehan</label>	
                        <div class="col-sm-4">
                            <input type="date" class="form-control" name="tgl_oleh">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label text-lg-right pt-2">Bukti Perolehan</label>	
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="bukti_oleh">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label text-lg-right pt-2">Harga Perolehan</label>	
                        <div class="col-sm-9">
                            <input type="number" class="form-control" name="harga_oleh">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label text-lg-right pt-2">Keterangan</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="keterangan" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label text-lg-right pt-2">Lampiran</label>
                        <div class="col-sm-9">
                            <input type="file" class="form-control" name="lampiran">
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="col-sm-3 control-label text-lg-right pt-2">Operator</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['namap']) ?>" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label text-lg-right pt-2"></label>
                        <div class="col-sm-8 text-lg-right pt-2">
                            <input type="reset" class="btn btn-warning" value="Reset">
                            <button type="submit" class="btn btn-primary">Tambahkan</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
<!-- end: page -->