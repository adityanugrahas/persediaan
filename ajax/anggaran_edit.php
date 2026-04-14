<?php
session_start();
include("../global/koneksi.php");
require_once("../global/ajax_header.php");

$id = htmlspecialchars($_REQUEST["id"] ?? '');
$ag = db_fetch($bp, "SELECT * FROM anggaran WHERE id_anggaran = :id", ['id' => $id]);

if (!$ag) {
    echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
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
            <h2 class="card-title">Penyesuaian Anggaran</h2>
            <p class="text-muted small mb-0">Fiscal Modification & Pagu Alignment</p>
        </header>
        <div class="card-body">
            <form id='edit_anggaran' method='post' action='?p=update&tab=anggaran' enctype='multipart/form-data' class='form-horizontal'>	
                <input type="hidden" name="id_anggaran" value="<?= htmlspecialchars($ag['id_anggaran']) ?>">
                <input type="hidden" name="pagu_lama" value="<?= htmlspecialchars($ag['pagu_anggaran']) ?>">
                
                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2">Nama Akun / Peruntukan</label>
                    <input type='text' name='akun' value="<?= htmlspecialchars($ag['akun_anggaran']) ?>" class='form-control' placeholder='Akun Anggaran' required style="border-radius:12px !important; font-weight:700;"/>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2">Pagu Saat Ini</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="border-radius: 12px 0 0 12px !important; background: var(--glass-medium);">Rp</span>
                        </div>
                        <input type='text' value="<?= number_format($ag['pagu_anggaran'], 0, ',', '.') ?>" class='form-control' disabled style="border-radius: 0 12px 12px 0 !important; font-weight:700;"/>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2">Revisi Pagu Baru</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="border-radius: 12px 0 0 12px !important; background: var(--glass-bright);">Rp</span>
                        </div>
                        <input type='number' min='0' name='pagu_baru' value="<?= $ag['pagu_anggaran'] ?>" class='form-control' required style="border-radius: 0 12px 12px 0 !important; font-weight:800; color: var(--primary);"/>
                    </div>
                    <small class="text-muted mt-1">Masukkan nominal baru untuk memperbarui total anggaran.</small>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2">Justifikasi Perubahan</label>
                    <textarea name='ket_pagu_baru' class='form-control' rows="2" placeholder='Alasan revisi anggaran...' style="border-radius: 12px !important;"></textarea>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2">Lampiran Revisi</label>
                    <input type='file' name='lampiran' class='form-control-file p-2' style="background:rgba(255,255,255,0.02); border-radius:8px; width:100%;"/>
                </div>

                <div class="row mt-5 pt-3" style="background: rgba(0,0,0,0.2); margin: 0 -30px -30px -30px; padding: 25px 30px; border-top: 1px solid var(--glass-border); border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
                    <div class="col-12 text-right">
                        <button type="button" class='btn btn-dark modal-dismiss px-4 mr-2' style="border-radius: 30px !important;">Batal</button>
                        <button type="submit" class='btn btn-primary px-5 modal-submit' style="border-radius: 30px !important; box-shadow: 0 10px 20px var(--primary-glow);">
                            <i class="fas fa-check-double mr-2"></i> Update Anggaran
                        </button>
                    </div>
                </div>	
            </form>
        </div>
    </section>
</div>
<?php require_once("../global/ajax_footer.php"); ?>