<?php
include("../global/koneksi.php");

$id = $_REQUEST["id"] ?? '';
$d = $_REQUEST["d"] ?? '';

if ($d == "bagian") {
    $bd = db_fetch($bp, "SELECT * FROM bagian WHERE id_seksi = :id", ['id' => $id]);
    if (!$bd) {
        echo "Data bagian tidak ditemukan.";
        exit;
    }
?>
<div id="custom-content" class="modal-block modal-block-md">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class='btn btn-dark btn-sm modal-dismiss'>X</button>
            </div>
            <h2 class="card-title">Edit Bagian</h2>
        </header>
        <div class="card-body">
            <form id='edit_bagian_form' method='post' action='?p=update&tab=bagian' class='form-horizontal'>	
                <input type="hidden" name="id_seksi" value="<?= htmlspecialchars($bd['id_seksi']) ?>">
                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Nama Bidang / Seksi / Sub</label>
                    <div class="col-lg-8">
                        <input type='text' name='bagian' value="<?= htmlspecialchars($bd['bagian']) ?>" class='form-control' placeholder='Nama Bidang / Seksi / Sub' required/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Keterangan</label>
                    <div class="col-lg-8">
                        <input type='text' name='ket' value="<?= htmlspecialchars($bd['keterangan']) ?>" class='form-control' placeholder='Keterangan'/>
                    </div>
                </div>
                <hr>
                <div class='text-right'>
                    <button type="button" class='btn btn-default modal-dismiss'>Batal</button>
                    <button type="submit" class='btn btn-success'><i class="fa fa-edit"></i> Update Bagian</button>
                </div>
            </form>
        </div>
    </section>
</div>
<?php 
} elseif ($d == "petugas") {
    // Note: The original code for petugas seemed inconsistent/placeholder.
    // We'll migrate it to PDO but it may need further correction if 'petugas' 
    // structure differs from the 'tahapan' fields used in old code.
    $data = db_fetch($bp, "SELECT * FROM petugas WHERE id_petugas = :id", ['id' => $id]);
    if (!$data) {
        echo "Data petugas tidak ditemukan.";
        exit;
    }
?>
<div id="custom-content" class="modal-block modal-block-md">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class='btn btn-dark btn-sm modal-dismiss'>X</button>
            </div>
            <h2 class="card-title">Edit Petugas</h2>
        </header>
        <div class="card-body">
            <form id='edit_petugas_form' method='post' action='?p=update&tab=petugas' enctype='multipart/form-data' class='form-horizontal'>	
                <input type="hidden" name="id_petugas" value="<?= htmlspecialchars($data['id_petugas']) ?>">
                <div class="form-group row">
                    <div class='col-sm-12 text-center mb-3'>
                        <?php $photo = (!empty($data['photo']) && file_exists("../img/users/" . $data['photo'])) ? $data['photo'] : "user.jpg"; ?>
                        <img src='img/users/<?= htmlspecialchars($photo) ?>' height='150' class="rounded shadow-sm">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 control-label text-lg-right pt-2">Nama</label>
                    <div class="col-lg-8">
                        <input type='text' name='nama' value="<?= htmlspecialchars($data['nama']) ?>" class='form-control' placeholder='Nama Lengkap' required/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 control-label text-lg-right pt-2">User ID / NIP</label>
                    <div class="col-lg-8">
                        <input type='text' name='users_id' value="<?= htmlspecialchars($data['users_id']) ?>" class='form-control' placeholder='NIP / User ID' required/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 control-label text-lg-right pt-2">Ganti Photo</label>
                    <div class="col-lg-8">
                        <input type='file' name='photo' class='form-control' />
                        <span class="help-block">Format file: .jpeg/.jpg/.png</span>
                    </div>
                </div>

                <hr>
                <div class='row'>
                    <div class='col-md-11 text-right'>
                        <button type="button" class='btn btn-default modal-dismiss'>Batal</button>
                        <button type="submit" class='btn btn-success'><i class="fa fa-edit"></i> Update Petugas</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
<?php 
} ?>