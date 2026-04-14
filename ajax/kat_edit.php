<?php
session_start();
include("../global/koneksi.php");

$id = $_REQUEST["id"] ?? '';
$kat = db_fetch($bp, "SELECT * FROM kategori WHERE id_kat = :id", ['id' => $id]);

if (!$kat) {
    echo "Kategori tidak ditemukan.";
    exit;
}
?>
<div id="custom-content" class="modal-block modal-block-md">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class='btn btn-dark btn-sm modal-dismiss'>X</button>
            </div>
            <h2 class="card-title">Edit Kategori</h2>
        </header>
        <div class="card-body">
            <form id='kat_edit_form' method='post' action='?p=update&tab=kat_edit' enctype='multipart/form-data' class='form-horizontal mb-lg'>	
                <input type="hidden" name="pemroses" value="<?= htmlspecialchars($_SESSION['idp']) ?>">
                <input type="hidden" name="id_kat" value="<?= htmlspecialchars($kat['id_kat']) ?>">
                
                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Nama Kategori</label>
                    <div class="col-lg-8">
                        <input type='text' name='nama_kat' class='form-control' placeholder='Nama Kategori Barang' value="<?= htmlspecialchars($kat['nama_kat']) ?>" required/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Keterangan</label>
                    <div class="col-lg-8">
                        <input type='text' name='ket' class='form-control' placeholder='Keterangan' value="<?= htmlspecialchars($kat['ket_kat']) ?>" required/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Operator</label>
                    <div class="col-lg-8">
                        <input type='text' class='form-control' value="<?= htmlspecialchars($_SESSION['namap']) ?>" disabled/>
                    </div>
                </div>
                
                <hr>
                <div class="form-group row">
                    <div class='col-md-12 text-right'>
                        <button type="button" class='btn btn-default modal-dismiss'>Batal</button>
                        <button type="submit" class='btn btn-success'><i class="fa fa-save"></i> Update Kategori</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>