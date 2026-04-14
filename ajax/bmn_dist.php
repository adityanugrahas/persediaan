<?php
session_start();
include("../global/koneksi.php");

$id_bmn = $_REQUEST["id_bmn"] ?? '';
$bm = db_fetch($bp, "SELECT * FROM bmn WHERE id_bmn = :id", ['id' => $id_bmn]);

if (!$bm) {
    echo "Data BMN tidak ditemukan.";
    exit;
}

$nm = htmlspecialchars($_REQUEST["nm"] ?? '');
$kat = htmlspecialchars($_REQUEST["kat"] ?? '');
$usage = db_fetch($bp, "SELECT SUM(jumlah_pakai) as digunakan FROM bmn_dist WHERE id_bmn = :id", ['id' => $bm['id_bmn']]);
$digunakan = $usage['digunakan'] ?? 0;
$sisa = $bm["jumlah_bmn"] - $digunakan;
?>
<div id="custom-content" class="modal-block modal-block-full">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class='btn btn-dark btn-sm modal-dismiss'>X</button>
            </div>
            <h2 class="card-title">Distribusi Data BMN</h2>
        </header>
        <div class="card-body">
            <form id='bmn_dist_form' method='post' action='?p=proadd&tab=bmn_dist' enctype='multipart/form-data' class='form-horizontal'>
                <input type="hidden" name="editor" value="<?= htmlspecialchars($_SESSION['idp']) ?>" />	
                <input type="hidden" name="id_bmn" value="<?= htmlspecialchars($bm['id_bmn']) ?>" />												  
                <input type="hidden" name="kat" value="<?= $kat ?>" />
                <input type="hidden" name="nm" value="<?= $nm ?>" />

                <div class="row">
                    <div class="col-md-6 border-right">
                        <div class="form-group row mb-3">
                            <div class="col-sm-12 text-center">
                                <?php $gambar = (!empty($bm["gambar"]) && file_exists("../img/barang/" . $bm["gambar"])) ? $bm["gambar"] : "barang.png"; ?>
                                <img src='img/barang/<?= htmlspecialchars($gambar) ?>' class="img-fluid rounded shadow-sm" style="max-height: 250px;">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2">Kode Barang</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value='<?= htmlspecialchars($bm['kode_bmn']) ?>' disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2">Nama Jenis Barang</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value='<?= htmlspecialchars($bm['nama_bmn']) ?>' disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2">Jumlah BMN</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value='<?= $bm['jumlah_bmn'] ?> <?= htmlspecialchars($bm['satuan']) ?>' disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2 font-weight-bold">Sudah Digunakan</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" value='<?= $digunakan ?>' disabled>
                            </div>
                            <label class="col-sm-2 control-label text-lg-right pt-2 font-weight-bold text-success">Sisa</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control is-valid" value='<?= $sisa ?>' disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2">Jumlah Dipakai</label>
                            <div class="col-sm-4">
                                <input type="number" min='1' max='<?= $sisa ?>' class="form-control" name="jumlah_pakai" required>
                            </div> 
                            <label class="col-sm-4 control-label pt-2"><?= htmlspecialchars($bm['satuan']) ?></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2">Bidang/Seksi</label>
                            <div class="col-sm-8">
                                <select name='seksi' class="form-control" required>
                                    <option value="">Pilih Bidang</option>
                                    <?php
                                    $sections = db_fetch_all($bp, "SELECT id_seksi, bagian FROM bagian ORDER BY id_seksi");
                                    foreach ($sections as $bid) {
                                        echo "<option value='" . htmlspecialchars($bid['id_seksi']) . "'>" . htmlspecialchars($bid['bagian']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2">Penanggung Jawab</label>
                            <div class="col-sm-8">
                                <select name='pengguna' class="form-control" required>
                                    <option value="">Pilih Penanggung Jawab</option>
                                    <?php
                                    $petugas = db_fetch_all($bp, "SELECT id_petugas, nama FROM petugas ORDER BY nama");
                                    foreach ($petugas as $p) {
                                        echo "<option value='" . htmlspecialchars($p['id_petugas']) . "'>" . htmlspecialchars($p['nama']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2">Tanggal Dipakai</label>
                            <div class="col-sm-8">
                                <input type="datetime-local" class="form-control" name="tgl_dist" required value="<?= date('Y-m-d\TH:i') ?>">
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2">Lokasi</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="lokasi" placeholder="Contoh: Ruang Kerja">
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2">Kondisi</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="kondisi" value="Baik">
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2">Lampiran Dokumentasi</label>
                            <div class="col-sm-8">
                                <input type="text" name="nama_lampiran" class="form-control mb-1" placeholder="Nama Dokumen">
                                <input type="file" name="lampiran" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2">Keterangan</label>
                            <div class="col-sm-8">
                                <textarea name="keterangan" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2"></label>
                            <div class="col-sm-8 text-right pt-2">
                                <button type="button" class='btn btn-default modal-dismiss'>Batal</button>
                                <input type="reset" class="btn btn-warning" value="Reset">
                                <button type="submit" class="btn btn-primary">Proses Distribusi</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
