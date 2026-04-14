<?php
include("../global/koneksi.php");
$nota_id = $_REQUEST["nota"];
$nt = db_fetch($bp, "SELECT n.*, p.nama FROM nota n JOIN petugas p ON n.pemroses = p.id_petugas WHERE n.id_nota = :id", ['id' => $nota_id]);

if (!$nt) {
    echo "Data tidak ditemukan.";
    exit;
}
?>
<div id="custom-content" class="modal-block modal-block-lg">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class="btn btn-dark btn-sm modal-dismiss">X</button>
            </div>
            <h2 class="card-title">Detail Penambahan Stok</h2>
        </header>
        <div class="card-body">
            <table class="table table-borderless table-sm">
                <tr><td width="150">Tanggal</td><td> : <?= htmlspecialchars($nt['tanggal']) ?></td></tr>
                <tr><td>Keterangan</td><td> : <?= htmlspecialchars($nt['keterangan']) ?></td></tr>
                <tr><td>Petugas</td><td> : <?= htmlspecialchars($nt['nama']) ?></td></tr>
            </table>

<?php
$items = db_fetch_all($bp, "SELECT i.*, b.nama_barang FROM stok_inout i JOIN stok_barang b ON i.id_barang = b.id_barang WHERE i.kode_nota = :kode", ['kode' => $nt['kode_nota']]);
if (count($items) > 0):
?>
            <table class="table table-hover table-bordered table-sm mt-3">
                <thead class="bg-light">
                    <tr>
                        <th width="30">No</th>
                        <th>Nama Barang</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-right">Harga Satuan</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
<?php
    $i = 1;
    $total = 0;
    foreach ($items as $br):
        $subtotal = $br["jml_in"] * $br["harga"];
        $total += $subtotal;
?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($br['nama_barang']) ?></td>
                        <td class="text-center"><?= $br['jml_in'] ?> <?= htmlspecialchars($br['satuan']) ?></td>
                        <td class="text-right"><?= number_format($br['harga']) ?></td>
                        <td class="text-right"><?= number_format($subtotal) ?></td>
                    </tr>
<?php endforeach; ?>
                </tbody>
                <tfoot class="bg-light">
                    <tr>
                        <th colspan="4" class="text-center">Total</th>
                        <th class="text-right"><?= number_format($total) ?></th>
                    </tr>
                </tfoot>
            </table>
<?php endif; ?>
            <hr>
            <footer class="panel-footer">
                <div class="text-right">
                    <button class="btn btn-default modal-dismiss">Tutup</button>
                </div>
            </footer>
            <?php if (!empty($nt['lampiran'])): ?>
            <div class="mt-3 text-center">
                <img src="lampiran/<?= htmlspecialchars($nt['lampiran']) ?>" style="max-width: 100%;" class="rounded shadow-sm">
            </div>
            <?php endif; ?>
        </div>
    </section>
</div>