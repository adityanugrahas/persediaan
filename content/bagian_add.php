					<!-- start: page -->
						<div class="row">
							<div class="col">
								<section class="card">
									<header class="card-header">
										<div class="card-actions">
											<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
										</div>
					
										<h2 class="card-title">Form Tambah Bidang/Seksi</h2>
									</header>
            <div class="card-body">
            <form id='add_petugas' method='post' action='?p=proadd&tab=bagian' enctype='multipart/form-data' class='form-horizontal mb-lg'>	
                    <div class="form-group row">
                        <label class="col-lg-3 control-label text-lg-right pt-2" for="induk">Induk</label>
                        <div class="col-lg-6">
                        <select name='induk' class='form-control'>
<?php
if (!empty($_REQUEST["induk"])) { 
    $in = db_fetch($bp, "SELECT id_seksi, bagian FROM bagian WHERE id_seksi = :id", ['id' => $_REQUEST['induk']]);
    if ($in) {
        echo "<option value='" . htmlspecialchars($in['id_seksi']) . "'>" . htmlspecialchars($in['bagian']) . "</option>";
    }
}
$bagians = db_fetch_all($bp, "SELECT * FROM bagian ORDER BY id_seksi ASC");
if (count($bagians) > 0) {
    foreach ($bagians as $bd) {
        echo "<option value='" . htmlspecialchars($bd['id_seksi']) . "'>" . htmlspecialchars($bd['bagian']) . "</option>";
    }
} else {
    echo "<option value='root'>root</option>";
}
?>                           
                        </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 control-label text-lg-right pt-2" for="bagian">Nama Bidang/Seksi / Sub</label>
                        <div class="col-lg-6">
                            <input type='text' name='bagian' class='form-control' placeholder='Nama Bidang / Seksi / Sub' required/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 control-label text-lg-right pt-2" for="ket">Keterangan</label>
                        <div class="col-lg-6">
                        <input type='text' name='ket' class='form-control' placeholder='Keterangan'/>
                        
                        <span class="help-block">ex : Ket. tambahan / uraian dari singkatan.</span>
                    </div>
                    </div>

                    <footer class='panel-footer'>
<div class='row'>
<div class='col-md-9 text-right'>
<button class='btn btn-success modal-submit'><i class="fa fa-plus"></i> Tambahkan</button>
<button class='btn btn-default' onclick="javascript:history.go(-1);">Batal</button>
</div>
</div>
</footer>	

                    
                </form>
            </div>
        </section>
    </div>
</div>
<!-- end: page -->