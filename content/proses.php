<?php
/**
 * Content: Proses Permintaan — Detail approval page
 * Migrated to PDO
 */

$kode_nota = $_REQUEST['id'] ?? '';
$items = db_fetch_all($bp, "SELECT i.*, b.nama_barang, b.jumlah_stok, b.satuan 
                            FROM stok_inout i 
                            JOIN stok_barang b ON i.id_barang = b.id_barang 
                            WHERE i.kode_nota = :kode", ['kode' => $kode_nota]);

if (empty($items)) {
    echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
    return;
}

$first = $items[0];
$sek = db_fetch($bp, "SELECT bagian FROM bagian WHERE id_seksi = :id", ['id' => $first['id_seksi']]);
$ptg = db_fetch($bp, "SELECT nama FROM petugas WHERE id_petugas = :id", ['id' => $first['petugas']]);
?>
<script>
function popUp(URL) {
    window.open(URL, '_blank', 'toolbar=0,scrollbars=1,location=0,width=800,height=1000,statusbar=0,menubar=0,resizable=1,left=300,top=100');
}
</script>

<section class="card shadow-lg">
    <header class="card-header">
        <div class="card-actions">
            <button onclick="popUp('cetak/cetakslip.php?kode=<?= htmlspecialchars($kode_nota) ?>')" class="btn btn-dark btn-sm"><i class="fa fa-print"></i> Cetak Slip</button>
        </div>
        <h2 class="card-title">Proses Permohonan Pemakaian Barang</h2>
        <p class="card-subtitle">Nota: <?= htmlspecialchars($kode_nota) ?> | Pemohon: <?= htmlspecialchars($ptg['nama'] ?? '') ?> (<?= htmlspecialchars($sek['bagian'] ?? '') ?>)</p>
    </header>
    <div class="card-body">
        <form method="post" action="?p=update&tab=permintaan">
            <input type="hidden" name="jum" value="<?= count($items) ?>">
            
            <table class="table table-bordered table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Barang yang Dimohon</th>
                        <th width="100">Stok Saat Ini</th>
                        <th width="100">Jml Diminta</th>													
                        <th width="150">Jml Disetujui</th>
                        <th>Catatan Unit Kerja</th>
                        <th>Catatan Admin (Opsional)</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $i = 0;
                foreach ($items as $req): 
                    $i++;
                ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td class="font-weight-bold"><?= htmlspecialchars($req['nama_barang']) ?></td>
                        <td class="text-center"><?= $req['jumlah_stok'] ?> <?= htmlspecialchars($req['satuan']) ?></td>
                        <td class="text-center font-weight-bold text-primary"><?= $req['jml_req'] ?></td>
                        <td>
                            <?php if ($req['status'] == '1'): ?>
                                <input type="hidden" name="id_inout[]" value="<?= htmlspecialchars($req['id_inout']) ?>">
                                <input type="hidden" name="id_barang[]" value="<?= htmlspecialchars($req['id_barang']) ?>">
                                <input type="hidden" name="jumlah_stok[]" value="<?= $req['jumlah_stok'] ?>">
                                <div class="input-group">
                                    <input type="number" name="jml_out[]" value="<?= $req['jml_req'] ?>" min="0" max="<?= $req['jumlah_stok'] ?>" class="form-control" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><?= htmlspecialchars($req['satuan']) ?></span>
                                    </div>
                                </div>
                            <?php else: ?>
                                <span class="badge badge-success"><?= $req['jml_out'] ?> <?= htmlspecialchars($req['satuan']) ?> (Disetujui)</span>
                            <?php endif; ?>
                        </td>
                        <td><small class="text-muted"><?= htmlspecialchars($req['keterangan']) ?></small></td>
                        <td>
                            <?php if ($req['status'] == '1'): ?>
                                <input type="text" name="catatan[]" class="form-control form-control-sm" value="<?= htmlspecialchars($req['catatan']) ?>" placeholder="E.g. Disetujui sebagian">
                            <?php else: ?>
                                <?= htmlspecialchars($req['catatan']) ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            
            <?php if ($first['status'] == '1'): ?>
            <div class="text-right mt-4">
                <hr>
                <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-check-circle"></i> Proses Approval (<?= count($items) ?> Item)</button>
            </div>
            <?php endif; ?>
        </form>
    </div>
</section>