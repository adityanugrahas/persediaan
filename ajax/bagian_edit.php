<?php
/**
 * AJAX Manage Sections: Update Department Info
 * Premium Glassmorphism Overhaul
 */
session_start();
include("../global/koneksi.php");
require_once("../global/ajax_header.php");

$id = htmlspecialchars($_REQUEST["id"] ?? '');
$bd = db_fetch($bp, "SELECT * FROM bagian WHERE id_seksi = :id", ['id' => $id]);

if (!$bd) {
    echo "<div class='p-5 text-center text-muted'>Unit data not found.</div>";
    require_once("../global/ajax_footer.php");
    exit;
}
?>
<div id="custom-content" class="modal-block modal-block-md">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class="btn btn-dark btn-sm modal-dismiss" title="Close">X</button>
            </div>
            <h2 class="card-title">Edit Struktur Unit</h2>
            <p class="text-muted small mb-0">Organizational Hierarchy Management</p>
        </header>
        <div class="card-body">
            <form id='edit_bagian' method='post' action='?p=update&tab=bagian' enctype='multipart/form-data' class='form-horizontal'>	
                <input type="hidden" name="id_seksi" value="<?= htmlspecialchars($bd['id_seksi']) ?>">
                
                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2">Unit Induk (Hierarchy Root)</label>
                    <select name='induk' class='form-control select2' style="border-radius:12px !important;">
                        <?php
                        $seksies = db_fetch_all($bp, "SELECT bagian, id_seksi FROM bagian ORDER BY urutan, id_seksi");
                        foreach ($seksies as $sk) {
                            $sel = ($bd['induk'] == $sk['id_seksi']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($sk['id_seksi']) . "' {$sel}>" . htmlspecialchars($sk['bagian']) . "</option>";
                        }
                        ?>
                    </select>
                </div>        

                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2">Nama Bidang / Seksi / Sub-Bagian</label>
                    <input type='text' name='bagian' value="<?= htmlspecialchars($bd['bagian']) ?>" class='form-control form-control-lg' placeholder='Contoh: Seksi Teknologi Informasi' required style="border-radius:12px !important; font-weight:700;"/>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2">Deskripsi / Jabatan Kunci</label>
                    <input type='text' name='ket' value="<?= htmlspecialchars($bd['keterangan']) ?>" class='form-control' placeholder='Contoh: Kepala Kantor / Kasubbag' style="border-radius:12px !important;"/>
                </div>

                <div class="row mt-4 pt-3" style="background: rgba(0,0,0,0.2); margin: 0 -30px -30px -30px; padding: 25px 30px; border-top: 1px solid var(--glass-border); border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
                    <div class="col-12 text-right">
                        <button type="button" class='btn btn-dark modal-dismiss px-4 mr-2' style="border-radius: 30px !important;">Batal</button>
                        <button type="submit" class='btn btn-primary px-5 modal-submit' style="border-radius: 30px !important; box-shadow: 0 10px 20px var(--primary-glow);">
                            <i class="fas fa-sitemap mr-2"></i> Update Struktur
                        </button>
                    </div>
                </div>	
            </form>
        </div>
    </section>
</div>

<?php 
require_once("../global/ajax_footer.php");
?>