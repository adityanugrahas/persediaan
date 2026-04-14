<?php
/**
 * AJAX: BMN Add Form
 * Migrated to PDO
 */
session_start();
include("../global/koneksi.php");

$id_kat = $_REQUEST["kat_bmn"] ?? '';
$kat_data = [];
if (!empty($id_kat)) {
    $kat_data = db_fetch($bp, "SELECT * FROM kat_bmn WHERE id_kat = :id", ['id' => $id_kat]);
}

$selected_kat_id = $kat_data["id_kat"] ?? "";
$selected_kat_name = $kat_data["nama_kat"] ?? "-- Pilih Kategori --";
?>
<div id="custom-content" class="modal-block modal-block-md">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class='btn btn-dark btn-sm modal-dismiss'>X</button>
            </div>
            <h2 class="card-title">Form Tambah Data BMN</h2>
        </header>
        <div class="card-body">
            <form id='add_bmn_form' method='post' action='?p=proadd&tab=bmn' enctype='multipart/form-data' class='form-horizontal'>
                <input type="hidden" name="editor" value="<?= htmlspecialchars($_SESSION['idp']) ?>" />	
                
                <div class="form-group row">
                    <label class="col-lg-3 control-label text-lg-right pt-2">Kategori</label>
                    <div class="col-lg-6">
                        <select name='kat_bmn' class='form-control' required>		
                            <option value="<?= htmlspecialchars($selected_kat_id) ?>"><?= htmlspecialchars($selected_kat_name) ?></option>
                            <?php
                            $kats = db_fetch_all($bp, "SELECT id_kat, nama_kat FROM kat_bmn WHERE status = 'ON' ORDER BY nama_kat ASC");
                            foreach ($kats as $kat) {
                                if ($kat['id_kat'] != $selected_kat_id) {
                                    echo "<option value='" . htmlspecialchars($kat['id_kat']) . "'>" . htmlspecialchars($kat['nama_kat']) . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class='col-lg-3 pt-1'>
                        <a href='ajax/bmn_kat_add.php' class='simple-ajax-modal btn btn-xs btn-success'><i class="fa fa-plus"></i> Baru</a>
                    </div>
                </div>											  
                
                <div class="form-group row">
                    <label class="col-sm-3 control-label text-lg-right pt-2">Kode Barang</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="kode" placeholder="Kode BMN / NUP">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-3 control-label text-lg-right pt-2">Nama Barang</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="nama_barang" required placeholder="Nama lengkap barang">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 control-label text-lg-right pt-2">Jumlah & Satuan</label>
                    <div class="col-sm-4">
                        <input type="number" min='1' class="form-control" name="jumlah" value="1">
                    </div>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="satuan" placeholder="Satuan (Pcs/Unit/dll)" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 control-label text-lg-right pt-2">Perolehan</label>	
                    <div class="col-sm-5">
                        <input type="date" class="form-control" name="tgl_oleh" value="<?= date('Y-m-d') ?>">
                        <small>Tanggal</small>
                    </div>
                    <div class="col-sm-4">
                        <input type="number" class="form-control" name="harga_oleh" placeholder="Harga">
                        <small>Harga (IDR)</small>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 control-label text-lg-right pt-2">Detail Bukti</label>	
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="bukti_oleh" placeholder="No. Kontrak / Faktur">
                        <small>Asal / Bukti Perolehan</small>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 control-label text-lg-right pt-2">Keterangan</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="keterangan" rows="2" placeholder="Spesifikasi / Kondisi"></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 control-label text-lg-right pt-2">Lampiran Foto</label>
                    <div class="col-sm-9">
                        <input type="file" class="form-control" name="lampiran">
                    </div>
                </div>  
                
                <hr>
                <div class="text-right">
                    <button type="button" class='btn btn-default modal-dismiss'>Batal</button>
                    <button type="submit" class='btn btn-primary'><i class="fa fa-plus-circle"></i> SimpancData</button>
                </div>
            </form>
        </div>
    </section>
</div>