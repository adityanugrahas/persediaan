<?php
session_start();
include("../global/koneksi.php");

$id_dist = $_REQUEST["id_dist"] ?? '';
$d = db_fetch($bp, "SELECT * FROM bmn_dist WHERE id_dist = :id", ['id' => $id_dist]);

if (!$d) {
    echo "Data distribusi tidak ditemukan.";
    exit;
}

$bm = db_fetch($bp, "SELECT * FROM bmn WHERE id_bmn = :id", ['id' => $d['id_bmn']]);
$sek = db_fetch($bp, "SELECT bagian, id_seksi FROM bagian WHERE id_seksi = :id", ['id' => $d['seksi']]);
$p = db_fetch($bp, "SELECT nama, id_petugas FROM petugas WHERE id_petugas = :id", ['id' => $d['pengguna']]);
$kat = htmlspecialchars($_REQUEST["kat"] ?? '');
?>
<div id="custom-content" class="modal-block modal-block-full">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class='btn btn-dark btn-sm modal-dismiss'>X</button>
            </div>
            <h2 class="card-title">Pengembalian Data BMN</h2>
        </header>
        <div class="card-body">
            <form id='bmn_kembali_form' method='post' action='?p=proadd&tab=bmn_kembali' enctype='multipart/form-data' class='form-horizontal'>
                <input type="hidden" name="editor" value="<?= htmlspecialchars($_SESSION['idp']) ?>" />	
                <input type="hidden" name="id_bmn" value="<?= htmlspecialchars($bm['id_bmn']) ?>" />												  
                <input type="hidden" name="id_dist" value="<?= htmlspecialchars($id_dist) ?>" />
                <input type="hidden" name="kat" value="<?= $kat ?>" />
                
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
                            <label class="col-sm-4 control-label text-lg-right pt-2 font-weight-bold">Bidang/Seksi</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value='<?= htmlspecialchars($sek['bagian'] ?? '-') ?>' disabled>
                                <input type="hidden" name="seksi" value='<?= htmlspecialchars($sek['id_seksi'] ?? '') ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2 font-weight-bold">Penanggung Jawab</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value='<?= htmlspecialchars($p['nama'] ?? '-') ?>' disabled>
                                <input type="hidden" name="pengguna" value='<?= htmlspecialchars($p['id_petugas'] ?? '') ?>'>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2">Jumlah Dipakai</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" value="<?= $d['jumlah_pakai'] ?>" disabled>
                            </div> 
                            <label class="col-sm-4 control-label pt-2"><?= htmlspecialchars($bm['satuan']) ?></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2 text-danger font-weight-bold">Jumlah Kembali</label>
                            <div class="col-sm-4">
                                <input type="number" min='1' max='<?= $d['jumlah_pakai'] ?>' class="form-control border-danger" name="jumlah_kembali" required>
                            </div> 
                            <label class="col-sm-4 control-label pt-2"><?= htmlspecialchars($bm['satuan']) ?></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2">Tanggal Kembali</label>
                            <div class="col-sm-8">
                                <input type="datetime-local" class="form-control" name="tgl_dist" required value="<?= date('Y-m-d\TH:i') ?>">
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2">Kodisi Saat Kembali</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="kondisi" value="Baik">
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-lg-right pt-2">Lampiran Dokumen</label>
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
                                <button type="submit" class="btn btn-primary">Proses Pengembalian</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
