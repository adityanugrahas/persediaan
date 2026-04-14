<?php
session_start();
include("../global/koneksi.php");
require_once("../global/ajax_header.php");
$tanggal=date("Y-m-d H:i:s");
if(!empty($_REQUEST["idb"])) { $idb=$_REQUEST["idb"]; } else { $idb="";}
if(!empty($_REQUEST["pg"])) { $pg=$_REQUEST["pg"]; } else { $pg="";}
?>
<div id="custom-content" class="modal-block modal-block-md">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class="btn btn-dark btn-sm modal-dismiss">X</button>
            </div>
            <h2 class="card-title">Kategori Barang Baru</h2>
            <p class="text-muted small mb-0">Inventory Classification Management</p>
        </header>
        <div class="card-body">
            <form id='kat_add_form' method='post' action='?p=proadd&tab=kat_add' enctype='multipart/form-data' class='form-horizontal'>	
                <input type="hidden" name="pg" value="<?= htmlspecialchars($pg) ?>">
                <input type="hidden" name="idb" value="<?= htmlspecialchars($idb) ?>">
                
                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2">Nama Kategori</label>
                    <input type='text' name='nama_kat' class='form-control form-control-lg' placeholder='Ex: Alat Tulis Kantor' required style="border-radius:12px !important; font-weight:700;"/>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small text-uppercase mb-2">Deskripsi Kategori</label>
                    <textarea name='ket' class='form-control' rows="2" placeholder='Penjelasan singkat tentang lingkup kategori ini...' required style="border-radius:12px !important;"></textarea>
                </div>

                <div class="row mt-5 pt-3" style="background: rgba(0,0,0,0.2); margin: 0 -30px -30px -30px; padding: 25px 30px; border-top: 1px solid var(--glass-border); border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
                    <div class="col-12 text-right">
                        <button type="button" class='btn btn-dark modal-dismiss px-4 mr-2' style="border-radius: 30px !important;">Batal</button>
                        <button type="submit" class='btn btn-primary px-5 modal-submit' style="border-radius: 30px !important; box-shadow: 0 10px 20px var(--primary-glow);">
                            <i class="fas fa-plus-circle mr-2"></i> Tambah Kategori
                        </button>
                    </div>
                </div>	
            </form>
        </div>
    </section>
</div>
<?php require_once("../global/ajax_footer.php"); ?>