<header class="page-header">
	<h2>Tambah Petugas</h2>

	<div class="right-wrapper text-right">
		<ol class="breadcrumbs">
			<li>
				<a href="index.php">
					<i class="fas fa-calendar"></i>
				</a>
			</li>
		</ol>

		<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fas fa-chevron-left"></i></a>
	</div>
</header>

<!-- start: page -->
	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
					</div>
	
					<h2 class="card-title">Form Tambah Petugas</h2>
				</header>
				<div class="card-body">
<?php
$bagians = db_fetch_all($bp, "SELECT bagian, id_seksi FROM bagian ORDER BY id_seksi");
if (count($bagians) > 0):
?>
				<form id='add_petugas' method='post' action='?p=proadd&tab=petugas' enctype='multipart/form-data' class='form-horizontal mb-lg'>
				<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">Seksi / Bidang</label>
							<div class="col-lg-6">
								<select name='seksi' class='form-control'>
<?php foreach ($bagians as $bd): ?>
									<option value='<?= htmlspecialchars($bd['id_seksi']) ?>'><?= htmlspecialchars($bd['bagian']) ?></option>
<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">Nama Petugas</label>
							<div class="col-lg-6">
								<input type='text' name='nama' class='form-control' placeholder='Nama Petugas' required/>
							</div>
						</div>
	
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">User ID / NIP</label>
							<div class="col-lg-6">
							<input type='text' name='users_id' class='form-control' placeholder='NIP / User ID'/>
							
							<span class="help-block">dipakai untuk login.</span>
						</div>
						</div>
	
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">Password</label>
							<div class="col-lg-6">
							<input type='password' name='pwd' class='form-control' placeholder='Password'/>
							</div>
						</div>
	
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">Level</label>
							<div class="col-lg-6">
								<select name='level' class='form-control'>
									<option value='opr'>operator</option>
									<option value='adm'>admin</option>
									<option value='su'>su</option>                            
								</select>
							</div>
						</div>
	
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">Photo</label>
							<div class="col-lg-6">
							<input type='file' name='photo' class='form-control' placeholder='Ganti Photo' />
							</div>
						</div>
	
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">Status</label>
							<div class="col-lg-3">
							<select name='status' class='form-control'>
								<option value='aktif'>aktif</option>
								<option value='non aktif'>non aktif</option>
							</select>
							</div>
						</div>
	
						<footer class='panel-footer'>
<div class='row'>
<div class='col-md-9 text-right'>
	<button class='btn btn-success modal-submit'><i class="fa fa-check"></i> Submit</button>
	<button class='btn btn-default modal-dismiss'>Cancel</button>
</div>
</div>
</footer>	
<?php else: ?>
    <label>Data Bidang / Seksi Masih Kosong. Silahkan <a href='?p=bagian_add' class='btn btn-sm btn-primary'>Tambahkan data Bidang / Seksi</a></label>
<?php endif; ?>
						
					</form>
				</div>
			</section>
		</div>
	</div>
<!-- end: page -->