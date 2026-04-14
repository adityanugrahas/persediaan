<!-- start: page -->
<?php
$id = $_SESSION["idp"];
$pt = db_fetch($bp, "SELECT p.*, b.bagian FROM petugas p JOIN bagian b ON p.seksi = b.id_seksi WHERE p.id_petugas = :id", ['id' => $id]);
?>
	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
					</div>
	
					<h2 class="card-title">Edit Data Saya</h2>
				</header>
				<div class="card-body">
<?php
$bagians = db_fetch_all($bp, "SELECT bagian, id_seksi FROM bagian ORDER BY id_seksi");
if (count($bagians) > 0):
?>
				<form id='edit_profil' method='post' action='?p=update&tab=petugas' enctype='multipart/form-data' class='form-horizontal mb-lg'>
                <input type="hidden" name="id_petugas" value="<?= htmlspecialchars($pt['id_petugas']) ?>">
                <input type="hidden" name="photo_lama" value="<?= htmlspecialchars($pt['photo']) ?>">
				
                        <div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2"></label>
							<div class="col-lg-6 text-center">
                            <img src='img/users/<?= htmlspecialchars($pt['photo'] ?: 'user.png') ?>' width="200" alt="Photo">
							</div>
						</div>
                        <div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">Nama Petugas</label>
							<div class="col-lg-6">
								<input type='text' name='nama' class='form-control' placeholder='Nama Petugas' value='<?= htmlspecialchars($pt['nama']) ?>' required/>
							</div>
						</div>
	
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">User ID / NIP</label>
							<div class="col-lg-6">
							<input type='text' name='users_id' class='form-control' placeholder='NIP / User ID' value='<?= htmlspecialchars($pt['users_id']) ?>' required>
							
							<span class="help-block">dipakai untuk login.</span>
						</div>
						</div>
	
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">Password</label>
							<div class="col-lg-6">
							<input type='password' name='pwd' class='form-control' placeholder='Password Baru (kosongkan jika tidak diganti)' value='<?= htmlspecialchars($pt['pwd']) ?>'/>
							</div>
						</div>
                        <div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">Level</label>
							<div class="col-lg-6">
								<select name='level' class='form-control'>
									<option value='<?= htmlspecialchars($pt['p_level']) ?>'><?= htmlspecialchars($pt['p_level']) ?></option>
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
									<option value='<?= htmlspecialchars($pt['seksi']) ?>'><?= htmlspecialchars($pt['bagian']) ?></option>
<?php foreach ($bagians as $bd): ?>
									<option value='<?= htmlspecialchars($bd['id_seksi']) ?>'><?= htmlspecialchars($bd['bagian']) ?></option>
<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">Photo</label>
							<div class="col-lg-6">
							<input type='file' name='photo' class='form-control' placeholder='Ganti Photo' />
                            <span class="help-block">Abaikan jika tidak ganti photo.</span>
							</div>
						</div>
	
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">Status</label>
							<div class="col-lg-3">
							<select name='status' class='form-control'>
								<option value='<?= htmlspecialchars($pt['p_status']) ?>'><?= htmlspecialchars($pt['p_status']) ?></option>
								<option value='aktif'>aktif</option>
								<option value='non aktif'>non aktif</option>
							</select>
							</div>
						</div>
	
						<footer class='panel-footer'>
<div class='row'>
<div class='col-md-9 text-right'>
    <input type='reset' class='btn btn-warning' value='Reset'>
	<button class='btn btn-success modal-submit'><i class="fa fa-check"></i> Submit</button>
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