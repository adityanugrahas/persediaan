<?php
include("../global/koneksi.php");
$kode = $_REQUEST["kode"];
$nt = db_fetch($bp, "SELECT i.*, b.nama_barang FROM stok_inout i JOIN stok_barang b ON i.id_barang = b.id_barang WHERE i.kode_nota = :kode LIMIT 1", ['kode' => $kode]);

if (!$nt) {
    echo "Data tidak ditemukan.";
    exit;
}

if ($nt["status"] == '1') { 
    $status = "<span class='text-danger'>Belum Disetujui</span>"; 
} elseif ($nt["status"] == '2') { 
    $status = "<span class='text-success'>Sudah Disetujui</span>";
} else {
    $status = "<span class='text-warning'>Pending</span>";
}

$petugas = db_fetch($bp, "SELECT nama FROM petugas WHERE id_petugas = :id", ['id' => $nt['petugas']]);
$bagian = db_fetch($bp, "SELECT bagian FROM bagian WHERE id_seksi = :id", ['id' => $nt['id_seksi']]);
?>
<div id="custom-content" class="modal-block modal-block-lg">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class="btn btn-dark btn-sm modal-dismiss">X</button>
            </div>
            <h2 class="card-title">Detail Permintaan Barang</h2>
        </header>
        <div class="card-body">
            <table class="table table-borderless table-sm">
                <tr><td width="150">Tanggal</td><td> : <?= htmlspecialchars($nt['tgl']) ?></td></tr>
                <tr><td>Petugas</td><td> : <?= htmlspecialchars($petugas['nama'] ?? '') ?></td></tr>
                <tr><td>Bidang / Seksi</td><td> : <?= htmlspecialchars($bagian['bagian'] ?? '') ?></td></tr>
                <tr><td>Status</td><td> : <?= $status ?></td></tr>
            </table>

<?php
$items = db_fetch_all($bp, "SELECT i.id_barang, i.jml_req, i.jml_out, i.satuan, i.status, i.keterangan, i.catatan, b.nama_barang 
                            FROM stok_inout i JOIN stok_barang b ON i.id_barang = b.id_barang 
                            WHERE i.kode_nota = :kode", ['kode' => $nt['kode_nota']]);
if (count($items) > 0):
?>
            <table class="table table-hover table-bordered table-sm mt-3">
                <thead class="bg-light">
                    <tr>
                        <th width="30">No</th>
                        <th>Nama Barang</th>
                        <th class="text-center">Permintaan</th>
                        <th class="text-center">Disetujui</th>
                        <th>Keterangan</th>
                        <th>Catatan Admin</th>
                    </tr>
                </thead>
                <tbody>
<?php
    $i = 1;
    foreach ($items as $item):
?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($item['nama_barang']) ?></td>
                        <td class="text-center"><?= $item['jml_req'] ?> <?= htmlspecialchars($item['satuan']) ?></td>
                        <td class="text-center"><?= $item['jml_out'] ?> <?= htmlspecialchars($item['satuan']) ?></td>
                        <td><?= htmlspecialchars($item['keterangan']) ?></td>
                        <td><?= htmlspecialchars($item['catatan']) ?></td>
                    </tr>
<?php endforeach; ?>
                </tbody>
            </table>
<?php endif; ?>
            <hr>
            <footer class="panel-footer">
                <div class="text-right">
                    <button class="btn btn-default modal-dismiss">Tutup</button>
                </div>
            </footer>	 
        </div>
    </section>
</div>