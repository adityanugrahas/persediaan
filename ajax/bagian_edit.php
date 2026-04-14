<?php
include("../global/koneksi.php");
$id = htmlspecialchars($_REQUEST["id"]);
$bd = db_fetch($bp, "SELECT * FROM bagian WHERE id_seksi = :id", ['id' => $id]);
?>
<div id="custom-content" class="modal-block modal-block-md">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
            </div>
            <h2 class="card-title">Edit Bagian</h2>
        </header>
        <div class="card-body">
            <form id='edit_bagian' method='post' action='?p=update&tab=bagian' enctype='multipart/form-data' class='form-horizontal mb-lg'>	
                <input type="hidden" name="id_seksi" value="<?= htmlspecialchars($bd['id_seksi']) ?>">
                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Seksi / Bidang Induk</label>
                    <div class="col-lg-6">
                        <select name='induk' class='form-control'>
<?php
$bdg = db_fetch($bp, "SELECT id_seksi, bagian FROM bagian WHERE id_seksi = :id", ['id' => $bd['induk']]);
if ($bdg) {
    echo "<option value='" . htmlspecialchars($bdg['id_seksi']) . "'>" . htmlspecialchars($bdg['bagian']) . "</option>";
}
$seksies = db_fetch_all($bp, "SELECT bagian, id_seksi FROM bagian ORDER BY id_seksi");
foreach ($seksies as $sk) {
    echo "<option value='" . htmlspecialchars($sk['id_seksi']) . "'>" . htmlspecialchars($sk['bagian']) . "</option>";
}
?>
                        </select>
                    </div>
                </div>        
                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Nama Bidang / Seksi / Sub</label>
                    <div class="col-lg-8">
                        <input type='text' name='bagian' value="<?= htmlspecialchars($bd['bagian']) ?>" class='form-control' required/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Keterangan</label>
                    <div class="col-lg-8">
                        <input type='text' name='ket' value="<?= htmlspecialchars($bd['keterangan']) ?>" class='form-control' placeholder='Keterangan'/>
                    </div>
                </div>
                <hr>
                <footer class='panel-footer'>
                    <div class='col-md-12 text-right'>
                        <button class='btn btn-success modal-submit'><i class="fa fa-edit"></i> Update</button>
                        <button class='btn btn-default modal-dismiss'>Batal</button>
                    </div>
                </footer>	
            </form>
        </div>
    </section>
</div>