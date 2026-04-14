<!-- start: page -->
	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
					</div>
					<h2 class="card-title">Form Tambah Jenis Barang</h2>
				</header>
<?php
if (!empty($_REQUEST["ps"])) {
	$ps = htmlspecialchars($_REQUEST["ps"]);
	$brg = db_fetch($bp, "SELECT * FROM pesanan WHERE id_pesanan = :id", ['id' => $ps]);
	$nama_barang = htmlspecialchars($brg['nama_barang'] ?? '');
	$satuan = htmlspecialchars($brg['satuan'] ?? '');
	$ket_barang = htmlspecialchars($brg['ket_barang'] ?? '');
} else {
	$ps = "";
	$nama_barang = "";
	$satuan = "";
	$ket_barang = "";
}
?>
				<div class="card-body">
					<form id='add_petugas' method='post' action='?p=proadd&tab=barang' enctype='multipart/form-data' class='form-horizontal mb-lg'>
					<input type="hidden" name="editor" value="<?= htmlspecialchars($_SESSION['idp']) ?>" />
					<input type="hidden" name="ps" value="<?= $ps ?>" />
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">Kategori</label>
							<div class="col-sm-4">
								<select name='kategori' class='form-control' required>		
								<?php
								$kats = db_fetch_all($bp, "SELECT id_kat, nama_kat FROM kategori WHERE status = 'ON' ORDER BY nama_kat");
								foreach ($kats as $kat) {
									echo "<option value='" . htmlspecialchars($kat['id_kat']) . "'>" . htmlspecialchars($kat['nama_kat']) . "</option>";
								}
								?>
								</select>
							</div>
							<div class='col-lg-2'><a href='?p=kat_add' class='btn btn-info'><i class='fa fa-plus'></i> Tambah Kategori </a></div>
						</div>							  
						<div class="form-group row">
							<label class="col-sm-3 control-label text-lg-right pt-2">Nama Jenis Barang</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="nama_barang" value="<?= $nama_barang ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3 control-label text-lg-right pt-2">Satuan</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="satuan" value="<?= $satuan ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3 control-label text-lg-right pt-2">Stok Minimal</label>
							<div class="col-sm-6">
								<input type="number" class="form-control" name="stok_minimal" required>
							</div>
						</div>	
						<div class="form-group row">
							<label class="col-sm-3 control-label text-lg-right pt-2">Harga Satuan</label>	
							<div class="col-sm-6">
								<input type="number" class="form-control" name="harga">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3 control-label text-lg-right pt-2">Keterangan</label>
							<div class="col-sm-6">
								<textarea class="form-control" name="keterangan" rows="3"><?= $ket_barang ?></textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3 control-label text-lg-right pt-2">Lampiran</label>
							<div class="col-sm-6">
								<input type="file" class="form-control" name="lampiran">
							</div>
						</div>  
						<div class="form-group row">
							<label class="col-sm-3 control-label text-lg-right pt-2">Operator</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['namap']) ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3 control-label text-lg-right pt-2"></label>
							<div class="col-sm-6 text-lg-right pt-2">
								<input type="reset" class="btn btn-warning" value="Reset">
								<input type="submit" class="btn btn-primary" value=" Tambahkan ">
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
<!-- end: page -->