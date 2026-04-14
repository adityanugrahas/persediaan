<?php
session_start();
require_once("../global/ajax_header.php");
?>
<div id="custom-content" class="modal-block modal-block-md">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class="btn btn-dark btn-sm modal-dismiss">X</button>
            </div>
            <h2 class="card-title">Data Anggaran Baru</h2>
            <p class="text-muted small mb-0">Fiscal Allocation & Budget Configuration</p>
        </header>
        <div class="card-body">
            <form id='anggaran_add' method='post' action='?p=proadd&tab=anggaran_add' enctype='multipart/form-data' class='form-horizontal'>	
                <div class="form-group mb-4">                    
                    <label class="font-weight-bold text-muted small text-uppercase mb-2" style="letter-spacing:0.05em;">Tahun Anggaran</label>
                    <input type='text' name='ta' class='form-control' placeholder='Ex: <?= date('Y') ?>' required style="border-radius: 12px !important; font-weight:700;"/>
                </div>

                <div class="form-group mb-4">                    
                    <label class="font-weight-bold text-muted small text-uppercase mb-2" style="letter-spacing:0.05em;">Nama Akun / Peruntukan</label>
                    <input type='text' name='akun' class='form-control' placeholder='Ex: ATK Kantor' required style="border-radius: 12px !important;"/>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2" style="letter-spacing:0.05em;">Nilai Pagu (IDR)</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="border-radius: 12px 0 0 12px !important; background: var(--glass-medium); font-weight:700;">Rp</span>
                        </div>
                        <input type='number' name='pagu' class='form-control text-right' placeholder='0' required style="border-radius: 0 12px 12px 0 !important; font-weight:700;"/>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2" style="letter-spacing:0.05em;">Keterangan Tambahan</label>
                    <textarea name='ket_akun' class='form-control' rows="2" placeholder='Detail anggaran...' style="border-radius: 12px !important;"></textarea>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2" style="letter-spacing:0.05em;">Dokumen Lampiran</label>
                    <div class="p-3" style="background: rgba(255,255,255,0.03); border: 2px dashed var(--glass-border); border-radius: 12px;">
                        <input type='file' name='lampiran' class='form-control-file' />
                        <p class="text-muted xs-text mt-2 mb-0">PDF, JPG, or PNG preferred (Max 2MB)</p>
                    </div>
                </div>

                <div class="row mt-5 pt-3" style="background: rgba(0,0,0,0.2); margin: 0 -30px -30px -30px; padding: 25px 30px; border-top: 1px solid var(--glass-border); border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
                    <div class="col-12 text-right">
                        <button type="button" class='btn btn-dark modal-dismiss px-4 mr-2' style="border-radius: 30px !important;">Batal</button>
                        <button type="submit" class='btn btn-primary px-5 modal-submit' style="border-radius: 30px !important; box-shadow: 0 10px 20px var(--primary-glow);">
                            <i class="fas fa-save mr-2"></i> Simpan Anggaran
                        </button>
                    </div>
                </div>	
            </form>
        </div>
    </section>
</div>
<?php require_once("../global/ajax_footer.php"); ?>