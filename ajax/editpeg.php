<?php
session_start();
include("../global/koneksi.php");

if ($_SESSION["levelp"] === "su") {
    $id = $_REQUEST["id"];
    $bd = db_fetch($bp, "SELECT * FROM petugas WHERE id_petugas = :id", ['id' => $id]);
    
    if (!$bd) {
        echo "Data tidak ditemukan.";
        exit;
    }

    $photo = (!empty($bd["photo"])) ? $bd["photo"] : "user.jpg";
?>
<div id="custom-content" class="modal-block modal-block-md">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class="btn btn-dark btn-sm modal-dismiss">X</button>
            </div>
            <h2 class="card-title">Edit Data Petugas</h2>
        </header>
        <div class="card-body">
            <form id='editpeg' method='post' action='?p=update&tab=petugas' enctype='multipart/form-data' class='form-horizontal mb-lg'>	
                <input type="hidden" name="id_petugas" value="<?= htmlspecialchars($bd['id_petugas']) ?>">
                <input type="hidden" name="photo_lama" value="<?= htmlspecialchars($bd['photo']) ?>">
                
                <div class="form-group row">
                    <label class="col-lg-3 control-label text-lg-right pt-2">Nama Petugas</label>
                    <div class="col-lg-6">
                        <input type='text' name='nama' class='form-control' placeholder='Nama Petugas' value="<?= htmlspecialchars($bd['nama']) ?>" required/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 control-label text-lg-right pt-2">User ID / NIP</label>
                    <div class="col-lg-6">
                        <input type='text' name='users_id' class='form-control' placeholder='NIP / User ID' value="<?= htmlspecialchars($bd['users_id']) ?>" required>
                        <span class="help-block">Dipakai untuk login.</span>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 control-label text-lg-right pt-2">Password</label>
                    <div class="col-lg-6">
                        <input type='password' name='pwd' class='form-control' placeholder='Password' value="<?= htmlspecialchars($bd['pwd']) ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 control-label text-lg-right pt-2">Level</label>
                    <div class="col-lg-6">
                        <select name='level' class='form-control'>
                            <option value='<?= htmlspecialchars($bd['p_level']) ?>'><?= htmlspecialchars($bd['p_level']) ?></option>
                            <option value='opr'>operator</option>
                            <option value='adm'>admin</option>
                            <option value='su'>su</option>                            
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 control-label text-lg-right pt-2">Seksi / Bidang</label>
                    <div class="col-lg-6">
                        <select name='seksi' class='form-control'>
                            <?php
                            $bdg = db_fetch($bp, "SELECT id_seksi, bagian FROM bagian WHERE id_seksi = :id", ['id' => $bd['seksi']]);
                            if ($bdg) {
                                echo "<option value='" . htmlspecialchars($bdg['id_seksi']) . "'>" . htmlspecialchars($bdg['bagian']) . "</option>";
                            }
                            
                            $ssk = db_fetch_all($bp, "SELECT bagian, id_seksi FROM bagian ORDER BY id_seksi");
                            foreach ($ssk as $sk) {
                                echo "<option value='" . htmlspecialchars($sk['id_seksi']) . "'>" . htmlspecialchars($sk['bagian']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 control-label text-lg-right pt-2">Photo</label>
                    <div class="col-lg-6">
                        <img src="img/users/<?= htmlspecialchars($photo) ?>" height="150" alt="User Photo" class="mb-2 d-block">
                        <input type='file' name='photo' class='form-control' placeholder='Ganti Photo' />
                        <span class="help-block">Abaikan jika tidak ganti foto.</span>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 control-label text-lg-right pt-2">Status</label>
                    <div class="col-lg-3">
                        <select name='status' class='form-control'>
                            <option value='<?= htmlspecialchars($bd['p_status']) ?>'><?= htmlspecialchars($bd['p_status']) ?></option>
                            <option value='aktif'>aktif</option>
                            <option value='non aktif'>non aktif</option>
                        </select>
                    </div>
                </div>
                <hr>
                <footer class='panel-footer'>
                        <div class='col-md-12 text-right'>
                            <button class='btn btn-default modal-dismiss'>Batal</button>
                            <button class='btn btn-success modal-submit'><i class="fa fa-edit"></i> Update</button>
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