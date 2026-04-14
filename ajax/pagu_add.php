<?php
session_start();
include("../global/koneksi.php");

$id = $_REQUEST["id"] ?? '';
$ag = db_fetch($bp, "SELECT * FROM anggaran WHERE id_anggaran = :id", ['id' => $id]);

if (!$ag) {
    echo "Data anggaran tidak ditemukan.";
    exit;
}

$kode = date("ymd-Hi");
$tanggal = date("Y-m-d H:i:s");
?>
<div id="custom-content" class="modal-block modal-block-md">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class='btn btn-dark btn-sm modal-dismiss'>X</button>
            </div>
            <h2 class="card-title">Tambah Pagu Anggaran</h2>
        </header>
        <div class="card-body">
            <form id='anggaran_add_form' method='post' action='?p=proadd&tab=pagu_add' enctype='multipart/form-data' class='form-horizontal'>	
                <input type="hidden" name="id_anggaran" value="<?= htmlspecialchars($ag['id_anggaran']) ?>">
                <input type="hidden" name="kode" value="<?= htmlspecialchars($kode) ?>">
                <input type="hidden" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>">
                <input type="hidden" name="pemroses" value="<?= htmlspecialchars($_SESSION['idp']) ?>">
                
                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Akun Anggaran</label>
                    <div class="col-lg-8">
                        <input type='text' value="<?= htmlspecialchars($ag['akun_anggaran']) ?>" class='form-control' disabled/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Ket. Anggaran</label>
                    <div class="col-lg-8">
                        <input type='text' value="<?= htmlspecialchars($ag['ket_anggaran']) ?>" class='form-control' disabled/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2 font-weight-bold">Pagu Tambahan</label>
                    <div class="col-lg-8">
                        <input type='number' name='pagu_tambahan' class='form-control' placeholder='0' required/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Keterangan Reff</label>
                    <div class="col-lg-8">
                        <input type='text' name='keterangan' class='form-control' placeholder='Contoh: Revisi Pagu atau Penambahan' required/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Lampiran (Jika Ada)</label>
                    <div class="col-lg-8">
                        <input type='file' name='lampiran' class='form-control' />
                    </div>
                </div>
                <hr>
                <div class='text-right'>
                    <button type="button" class='btn btn-default modal-dismiss'>Batal</button>
                    <button type="submit" class='btn btn-success'><i class="fa fa-save"></i> Simpan Pagu</button>
                </div>
            </form>
        </div>
    </section>
</div>