<?php
/**
 * Content: BMN Distribution Modal
 * Migrated to PDO
 */
$id_bmn = $_REQUEST["id_bmn"] ?? '';
$bm = db_fetch($bp, "SELECT * FROM bmn WHERE id_bmn = :id", ['id' => $id_bmn]);

if (!$bm) {
    echo "Data BMN tidak ditemukan.";
    exit;
}
?>
<div id="custom-content" class="modal-block modal-block-md">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class='btn btn-dark btn-sm modal-dismiss'>X</button>
            </div>
            <h2 class="card-title">Distribusi Data BMN</h2>
        </header>
        <div class="card-body">
            <form id='bmn_dist_form' method='post' action='?p=update&tab=bmn_dist' enctype='multipart/form-data' class='form-horizontal'>
                <input type="hidden" name="editor" value="<?= htmlspecialchars($_SESSION['idp']) ?>" />	
                <input type="hidden" name="id_bmn" value="<?= htmlspecialchars($bm['id_bmn']) ?>" />												  
                
                <div class="form-group row justify-content-center">
                    <div class="col-sm-8 text-center">
                        <img src='img/barang/<?= htmlspecialchars($bm['gambar'] ?: 'barang.png') ?>' class="img-fluid rounded shadow-sm mb-3" style="max-height: 200px;">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-3 control-label text-lg-right pt-2 font-weight-bold">Barang</label>
                    <div class="col-sm-9">
                        <p class="form-control-static mb-0"><?= htmlspecialchars($bm['kode_bmn']) ?> - <?= htmlspecialchars($bm['nama_bmn']) ?></p>
                        <span class="badge badge-info"><?= $bm['jumlah_bmn'] ?> <?= htmlspecialchars($bm['satuan']) ?> tersedia</span>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 control-label text-lg-right pt-2">Unit Penerima</label>
                    <div class="col-sm-9">
                        <select name="id_seksi" class="form-control" required>
                            <option value="">-- Pilih Bidang/Seksi --</option>
                            <?php
                            $bagians = db_fetch_all($bp, "SELECT id_seksi, bagian FROM bagian ORDER BY urutan ASC");
                            foreach ($bagians as $bg) {
                                echo "<option value='" . htmlspecialchars($bg['id_seksi']) . "'>" . htmlspecialchars($bg['bagian']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 control-label text-lg-right pt-2">Jumlah Distribusi</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input type="number" name="jumlah" min="1" max="<?= $bm['jumlah_bmn'] ?>" class="form-control" placeholder="0" required>
                            <div class="input-group-append">
                                <span class="input-group-text"><?= htmlspecialchars($bm['satuan']) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 control-label text-lg-right pt-2">Keterangan</label>
                    <div class="col-sm-9">
                        <textarea name="keterangan" class="form-control" placeholder="E.g. Untuk ruang rapat"></textarea>
                    </div>
                </div>
                  
                <hr>
                <div class="text-right">
                    <button type="button" class='btn btn-default modal-dismiss'>Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-share-square"></i> Distribusikan</button>
                </div>
            </form>
        </div>
    </section>
</div>