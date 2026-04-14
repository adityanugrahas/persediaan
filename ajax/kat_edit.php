<?php
session_start();
include("../global/koneksi.php");
require_once("../global/ajax_header.php");

$id = $_REQUEST["id"] ?? '';
$kat = db_fetch($bp, "SELECT * FROM kategori WHERE id_kat = :id", ['id' => $id]);

if (!$kat) {
    echo "<div class='alert alert-danger'>Kategori tidak ditemukan.</div>";
    require_once("../global/ajax_footer.php");
    exit;
}
?>
<div id="custom-content" class="modal-block modal-block-md">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class="btn btn-dark btn-sm modal-dismiss">X</button>
            </div>
            <h2 class="card-title">Update Kategori</h2>
            <p class="text-muted small mb-0">Structural Inventory Modification</p>
        </header>
        <div class="card-body">
            <form id='kat_edit_form' method='post' action='?p=update&tab=kat_edit' enctype='multipart/form-data' class='form-horizontal'>	
                <input type="hidden" name="pemroses" value="<?= htmlspecialchars($_SESSION['idp']) ?>">
                <input type="hidden" name="id_kat" value="<?= htmlspecialchars($kat['id_kat']) ?>">
                
                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2">Nama Kategori</label>
                    <input type='text' name='nama_kat' class='form-control form-control-lg' placeholder='Ex: Alat Tulis Kantor' value="<?= htmlspecialchars($kat['nama_kat']) ?>" required style="border-radius:12px !important; font-weight:700;"/>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2">Deskripsi Kategori</label>
                    <textarea name='ket' class='form-control' rows="2" placeholder='Penjelasan lingkup kategori...' required style="border-radius:12px !important;"><?= htmlspecialchars($kat['ket_kat']) ?></textarea>
                </div>

                <div class="row mt-4 pt-3" style="background: rgba(0,0,0,0.1); margin: 0 -30px -30px -30px; padding: 25px 30px; border-top: 1px solid var(--glass-border);">
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-edit text-primary mr-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <p class="mb-0 small text-white font-weight-bold"><?= htmlspecialchars($_SESSION['namap']) ?></p>
                                <p class="mb-0 text-muted xs-text" style="font-size: 0.65rem; text-transform:uppercase; letter-spacing:0.05em;">Current Editor</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 text-right">
                        <button type="button" class='btn btn-dark modal-dismiss px-4 mr-2' style="border-radius: 30px !important;">Batal</button>
                        <button type="submit" class='btn btn-primary px-5 modal-submit' style="border-radius: 30px !important; box-shadow: 0 10px 20px var(--primary-glow);">
                            <i class="fas fa-sync-alt mr-2"></i> Update
                        </button>
                    </div>
                </div>	
            </form>
        </div>
    </section>
</div>
<?php require_once("../global/ajax_footer.php"); ?>