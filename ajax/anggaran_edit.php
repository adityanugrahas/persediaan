<?php
include("../global/koneksi.php");
$id = htmlspecialchars($_REQUEST["id"]);
$ag = db_fetch($bp, "SELECT * FROM anggaran WHERE id_anggaran = :id", ['id' => $id]);
?>
<div id="custom-content" class="modal-block modal-block-md">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
            </div>
            <h2 class="card-title">Edit Anggaran</h2>
        </header>
        <div class="card-body">
            <form id='edit_bagian' method='post' action='?p=update&tab=anggaran' enctype='multipart/form-data' class='form-horizontal mb-lg'>	
                <input type="hidden" name="id_anggaran" value="<?= htmlspecialchars($ag['id_anggaran']) ?>">
                <input type="hidden" name="pagu_lama" value="<?= htmlspecialchars($ag['pagu_anggaran']) ?>">
                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Akun</label>
                    <div class="col-lg-8">
                        <input type='text' name='akun' value="<?= htmlspecialchars($ag['akun_anggaran']) ?>" class='form-control' placeholder='Akun Anggaran' required/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Keterangan Akun</label>
                    <div class="col-lg-8">
                        <input type='text' name='ket_akun' value="<?= htmlspecialchars($ag['ket_anggaran']) ?>" class='form-control' placeholder='Keterangan'/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Edit Pagu</label>
                    <div class="col-lg-8">
                        <input type='number' min='0' name='pagu_baru' value="<?= $ag['pagu_anggaran'] ?>" class='form-control'/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Keterangan Perubahan</label>
                    <div class="col-lg-8">
                        <input type='text' name='ket_pagu_baru' value="" class='form-control' placeholder='Keterangan Perubahan Pagu'/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Lampiran Data Dukung</label>
                    <div class="col-lg-8">
                        <input type='file' name='lampiran' class='form-control'/>
                    </div>
                </div>
                <hr>
                <footer class='panel-footer'>
                    <div class='col-md-12 text-right'>
                        <button class='btn btn-primary modal-dismiss'>Batal</button>
                        <input type='reset' class='btn btn-default modal-reset' value='Reset'>
                        <button class='btn btn-success modal-submit'><i class="fa fa-edit"></i> Update</button>
                    </div>
                </footer>	
            </form>
        </div>
    </section>
</div>