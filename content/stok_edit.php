<!-- start: page -->
<?php
if ($_SESSION["levelp"] === "su") {
    $id = strip_tags($_REQUEST["id"]);
    $br = db_fetch($bp, "SELECT * FROM stok_barang WHERE id_barang = :id", ['id' => $id]);
    $barang = (!empty($br["gambar"]) && file_exists("img/barang/" . $br["gambar"])) ? $br["gambar"] : "barang.png";
?>
	<div class="row">
		<div class="col-6" style="float:none;margin:auto;">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
					</div>
	
					<h2 class="card-title">Form Edit Barang</h2>
				</header>
				<div class="card-body">
					<center>
                		<span class="highlight" style='font-weight:bolder'><?= htmlspecialchars($br['nama_barang']) ?></span><br>
                          <img src="img/barang/<?= htmlspecialchars($barang) ?>" height="200" alt="<?= htmlspecialchars($br['nama_barang']) ?>">
                          <br><span class="highlight">Jumlah Stok : <?= $br['jumlah_stok'] ?>
					</span>
					</center> <hr>
					<form id='edit_barang' method='post' action='?p=update&tab=barang' enctype='multipart/form-data' class='form-horizontal mb-lg'>
					<input type="hidden" name="editor" value="<?= htmlspecialchars($_SESSION['idp']) ?>" />	
					<input type="hidden" name="lampiran_lama" value="<?= htmlspecialchars($br['gambar']) ?>" />	
					<input type="hidden" name="id_barang" value="<?= htmlspecialchars($br['id_barang']) ?>" />	
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">Kategori</label>
							<div class="col-sm-5">
								<select name='kategori' class='form-control' required>		
								<?php
								$kats = db_fetch_all($bp, "SELECT id_kat, nama_kat FROM kategori WHERE status = 'ON' ORDER BY nama_kat");
								foreach ($kats as $kat) {
									$sel = ($kat['id_kat'] === $br['kategori']) ? " selected" : "";
									echo "<option value='" . htmlspecialchars($kat['id_kat']) . "'" . $sel . ">" . htmlspecialchars($kat['nama_kat']) . "</option>";
								}
								?>
								</select>
							</div>
							<div class='col-lg-4'>
    <a class='simple-ajax-modal modal-with-form btn btn-success' href='ajax/kat_add.php?pg=stok_edit&idb=<?= htmlspecialchars($br['id_barang']) ?>'><i class='fa fa-plus'></i> Tambah Kategori </a>
							</div>
						</div>							  
						<div class="form-group row">
							<label class="col-sm-3 control-label text-lg-right pt-2">Nama Jenis Barang</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="nama_barang" value="<?= htmlspecialchars($br['nama_barang']) ?>" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3 control-label text-lg-right pt-2">Satuan</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="satuan" value="<?= htmlspecialchars($br['satuan']) ?>" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3 control-label text-lg-right pt-2">Stok Minimal</label>
							<div class="col-sm-9">
								<input type="number" class="form-control" name="batas_stok" value="<?= $br['stok_minimal'] ?>" required>
							</div>
						</div>	
						<div class="form-group row">
							<label class="col-sm-3 control-label text-lg-right pt-2">Harga Satuan</label>	
							<div class="col-sm-9">
								<input type="number" class="form-control" name="harga" value="<?= $br['harga_satuan'] ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3 control-label text-lg-right pt-2">Keterangan</label>
							<div class="col-sm-9">
								<textarea class="form-control" name="keterangan" rows="3"><?= htmlspecialchars($br['keterangan']) ?></textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3 control-label text-lg-right pt-2">Ganti Gambar</label>
							<div class="col-sm-9">
								<input type="file" class="form-control" name="lampiran_baru">
								<span class="help-block">Abaikan jika tidak ganti gambar.</span>
							</div>
						</div>  
						<div class="form-group row">
							<label class="col-sm-3 control-label text-lg-right pt-2">Status</label>
							<div class="col-sm-9">
								<select name="aktif" class="form-control">
								<option value="<?= htmlspecialchars($br['aktif']) ?>"><?= htmlspecialchars($br['aktif']) ?></option>
								<option value="ya">ya</option>
								<option value="tidak">tidak</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3 control-label text-lg-right pt-2">Operator</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['namap']) ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3 control-label text-lg-right pt-2"></label>
							<div class="col-sm-9 text-lg-right pt-2">
								<a href="javascript:history.go(-1)" class='btn btn-success'><i class='fa fa-arrow-left'></i> Kembali</a>
								<button type="reset" class="btn btn-warning"><i class='fa fa-redo'></i> Reset</button>
								<button type="submit" class="btn btn-danger"><i class='fa fa-check'></i> Update</button>
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
<?php
} else {
    header("Location: index.php");
    exit;
}
?>
<!-- end: page -->