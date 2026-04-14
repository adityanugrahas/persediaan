<?php
/**
 * AJAX Manage Petugas: Update Profile
 * Premium Glassmorphism Overhaul
 */
session_start();
include("../global/koneksi.php");
require_once("../global/ajax_header.php");

if ($_SESSION["levelp"] === "su") {
    $id = $_REQUEST["id"] ?? '';
    $bd = db_fetch($bp, "SELECT * FROM petugas WHERE id_petugas = :id", ['id' => $id]);
    
    if (!$bd) {
        echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
        require_once("../global/ajax_footer.php");
        exit;
    }

    $photo = (!empty($bd["photo"]) && file_exists("../img/users/" . $bd["photo"])) ? $bd["photo"] : "user.jpg";
?>
<div id="custom-content" class="modal-block modal-block-md">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class="btn btn-dark btn-sm modal-dismiss" title="Close">X</button>
            </div>
            <h2 class="card-title">Manajemen Akun Petugas</h2>
            <p class="text-muted small mb-0">System Authority & Identity Configuration</p>
        </header>
        <div class="card-body">
            <form id='editpeg' method='post' action='?p=update&tab=petugas' enctype='multipart/form-data' class='form-horizontal'>	
                <input type="hidden" name="id_petugas" value="<?= htmlspecialchars($bd['id_petugas']) ?>">
                <input type="hidden" name="photo_lama" value="<?= htmlspecialchars($bd['photo']) ?>">
                
                <div class="row mb-4 align-items-center">
                    <div class="col-md-4 text-center">
                        <div class="position-relative d-inline-block">
                            <img src="img/users/<?= htmlspecialchars($photo) ?>" style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 4px solid var(--glass-border); box-shadow: 0 10px 20px rgba(0,0,0,0.4);" alt="User Photo">
                            <div class="position-absolute" style="bottom: 5px; right: 5px;">
                                <span class="badge badge-<?= $bd['p_status'] == 'aktif' ? 'success' : 'danger' ?> p-2 shadow-lg" style="border-radius: 50%; width: 30px; height: 30px; display: flex; border: 3px solid #1a1a2e;"><i class="fas fa-check" style="font-size: 0.7rem;"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-muted small text-uppercase mb-1">Nama Lengkap</label>
                            <input type='text' name='nama' class='form-control form-control-lg' value="<?= htmlspecialchars($bd['nama']) ?>" required style="border-radius:12px !important; font-weight:700;"/>
                        </div>
                        <div class="form-group mb-0">
                            <label class="font-weight-bold text-muted small text-uppercase mb-1">Authentication ID</label>
                            <input type='text' name='users_id' class='form-control' value="<?= htmlspecialchars($bd['users_id']) ?>" required style="border-radius:12px !important;">
                            <small class="text-muted xs-text">This is used for system login.</small>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 1px solid var(--glass-border); margin: 25px 0;">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted small text-uppercase mb-2">Password Baru</label>
                            <input type='password' name='pwd' class='form-control' placeholder='••••••••' style="border-radius:12px !important;">
                            <small class="text-muted xs-text">Leave blank to keep current password.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted small text-uppercase mb-2">Authority Level</label>
                            <select name='level' class='form-control' style="border-radius:12px !important;">
                                <option value='su' <?= $bd['p_level'] == 'su' ? 'selected' : '' ?>>Super User</option>
                                <option value='adm' <?= $bd['p_level'] == 'adm' ? 'selected' : '' ?>>Administrator</option>
                                <option value='opr' <?= $bd['p_level'] == 'opr' ? 'selected' : '' ?>>Operator</option>                            
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted small text-uppercase mb-2">Unit / Bagian</label>
                            <select name='seksi' class='form-control' style="border-radius:12px !important;">
                                <?php
                                $ssk = db_fetch_all($bp, "SELECT bagian, id_seksi FROM bagian ORDER BY id_seksi");
                                foreach ($ssk as $sk) {
                                    $sel = ($bd['seksi'] == $sk['id_seksi']) ? 'selected' : '';
                                    echo "<option value='" . htmlspecialchars($sk['id_seksi']) . "' {$sel}>" . htmlspecialchars($sk['bagian']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted small text-uppercase mb-2">Account Status</label>
                            <select name='status' class='form-control' style="border-radius:12px !important;">
                                <option value='aktif' <?= $bd['p_status'] == 'aktif' ? 'selected' : '' ?>>Active / Verified</option>
                                <option value='non aktif' <?= $bd['p_status'] == 'non aktif' ? 'selected' : '' ?>>Suspended / Locked</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-2">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2">Update Photo Profile</label>
                    <div class="p-3" style="background: rgba(255,255,255,0.02); border: 2px dashed var(--glass-border); border-radius: 12px;">
                        <input type='file' name='photo' class='form-control-file' />
                    </div>
                </div>

                <div class="row mt-4 pt-3" style="background: rgba(0,0,0,0.2); margin: 0 -30px -30px -30px; padding: 25px 30px; border-top: 1px solid var(--glass-border); border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
                    <div class="col-12 text-right">
                        <button type="button" class='btn btn-dark modal-dismiss px-4 mr-2' style="border-radius: 30px !important;">Batal</button>
                        <button type="submit" class='btn btn-primary px-5 modal-submit' style="border-radius: 30px !important; box-shadow: 0 10px 20px var(--primary-glow);">
                            <i class="fas fa-user-check mr-2"></i> Update Akun
                        </button>
                    </div>
                </div>	
            </form>
        </div>
    </section>
</div>
<?php
} else {
    echo "<div class='p-5 text-center'><i class='fas fa-lock fa-3x mb-3 text-danger'></i><h4>Access Denied</h4></div>";
}
require_once("../global/ajax_footer.php");
?>