<!-- start: page -->
<script>
function popUp(URL) {
    const day = new Date();
    const id = day.getTime();
    window.open(URL, id, 'toolbar=0,scrollbars=1,location=0,width=800,height=1000,statusbar=0,menubar=0,resizable=0,left=300,top=100');
}
</script>
<?php
$id_seksi = $_REQUEST["id_seksi"] ?? '';
$kon_seksi = !empty($id_seksi) ? "AND d.seksi = :id_seksi" : "";
$id_bmn = $_REQUEST["id_bmn"] ?? '';

$b = db_fetch($bp, "SELECT * FROM bmn WHERE id_bmn = :id", ['id' => $id_bmn]);
if (!$b) {
    echo "Data tidak ditemukan.";
    return;
}
$k = db_fetch($bp, "SELECT nama_kat, id_kat FROM kat_bmn WHERE id_kat = :id", ['id' => $b['kat_bmn']]);
?>
<div class="row">
    <div class="col">
        <section class="card">
            <header class="card-header">
                <div class="card-actions">
                    <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                </div>
                <h2 class="card-title">
                    <a href='?p=bmn'>Data BMN</a> <i class='fa fa-angle-right'></i> 
                    <a href='?p=bmn&kat=<?= htmlspecialchars($b['kat_bmn']) ?>'><?= htmlspecialchars($k['nama_kat'] ?? '') ?></a> <i class='fa fa-angle-right'></i> 
                    <?= htmlspecialchars($b['nama_bmn']) ?> <i class='fa fa-angle-right'></i> Detail Pemakai
                </h2>
            </header>
            <div class="card-body">
                <div class="alert alert-info nomargin">
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-sm table-borderless mb-0">
                                <tr><td width="150">Kategori</td><td>: <?= htmlspecialchars($k['nama_kat'] ?? '') ?></td></tr>
                                <tr><td>Kode</td><td>: <?= htmlspecialchars($b['kode_bmn']) ?></td></tr>
                                <tr><td>Nama BMN</td><td>: <?= htmlspecialchars($b['nama_bmn']) ?></td></tr>
                                <tr><td>Jumlah</td><td>: <?= $b['jumlah_bmn'] ?> <?= htmlspecialchars($b['satuan']) ?></td></tr>
                                <tr><td>Keterangan</td><td>: <?= htmlspecialchars($b['keterangan']) ?></td></tr>
                            </table>
                        </div>
                        <div class="col-md-4 text-center">
                            <?php $gambar = (!empty($b["gambar"]) && file_exists("img/barang/" . $b["gambar"])) ? $b["gambar"] : "barang.png"; ?>
                            <img class="img-fluid rounded shadow-sm" src="img/barang/<?= htmlspecialchars($gambar) ?>" style="max-height: 150px;">
                        </div>
                    </div>
                </div>
                <table class="table table-hover table-bordered table-striped mb-0 mt-3" id="datatable-tabletools">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Barang</th>
                            <th>Tanggal</th>
                            <th>Seksi</th>
                            <th>Penanggung Jawab</th>
                            <th>Jumlah Pakai</th>
                            <th>Jumlah Kembali</th>
                            <th>Keterangan</th>
                            <th>Lampiran</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
$i = 1;
$total = 0;
$total_kembali = 0;
$params = ['id_bmn' => $id_bmn];
if (!empty($id_seksi)) $params['id_seksi'] = $id_seksi;

$items = db_fetch_all($bp, "SELECT d.*, b.nama_bmn, b.satuan, s.bagian, p.nama as petugas_nama
                             FROM bmn_dist d 
                             JOIN bmn b ON d.id_bmn = b.id_bmn 
                             LEFT JOIN bagian s ON d.seksi = s.id_seksi
                             LEFT JOIN petugas p ON d.pengguna = p.id_petugas
                             WHERE d.id_bmn = :id_bmn $kon_seksi 
                             ORDER BY d.tgl_dist", $params);

foreach ($items as $d):
    $style = ($d["jumlah_kembali"] > 0) ? "class='table-secondary'" : "";
    $jp = ($d["jumlah_pakai"] > 0) ? $d["jumlah_pakai"] . " " . $d["satuan"] : "";
    $jk = ($d["jumlah_kembali"] > 0) ? $d["jumlah_kembali"] . " " . $d["satuan"] : "";
?>
                        <tr <?= $style ?>>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($d['nama_bmn']) ?></td>
                            <td><?= htmlspecialchars($d['tgl_dist']) ?></td>
                            <td><?= htmlspecialchars($d['bagian'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($d['petugas_nama'] ?? '-') ?></td>
                            <td><?= $jp ?></td>
                            <td><?= $jk ?></td>
                            <td><?= htmlspecialchars($d['keterangan']) ?></td>
                            <td>
                                <?php if (!empty($d["lampiran"])): ?>
                                <button type="button" onclick="popUp('lampiran/<?= htmlspecialchars($d['lampiran']) ?>')" class="btn btn-xs btn-default"><i class="fa fa-file"></i> <?= htmlspecialchars($d['nama_lampiran']) ?></button>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <?php if ($d["jumlah_pakai"] > 0): ?>
                                    <a class='simple-ajax-modal btn btn-xs btn-warning' href='ajax/bmn_kembali.php?id_dist=<?= htmlspecialchars($d['id_dist']) ?>&kat=<?= htmlspecialchars($k['id_kat'] ?? '') ?>' title="Kembalikan"><i class='fa fa-reply'></i></a>
                                    <?php endif; ?>
                                    <a class='simple-ajax-modal btn btn-xs btn-danger' href='ajax/delete.php?d=bmn_dist&id_dist=<?= htmlspecialchars($d['id_dist']) ?>&kat=<?= htmlspecialchars($k['id_kat'] ?? '') ?>&id_bmn=<?= htmlspecialchars($id_bmn) ?>' title="Hapus"><i class='fa fa-trash'></i></a>
                                </div>
                            </td>
                        </tr>
<?php
    $total += $d["jumlah_pakai"];
    $total_kembali += $d["jumlah_kembali"];
endforeach;
?>
                    </tbody>
                    <tfoot class="bg-light">
                        <tr class="font-weight-bold">
                            <th colspan="5" class="text-center">JUMLAH</th>
                            <th><?= $total ?></th>
                            <th><?= $total_kembali ?></th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>
    </div>
</div>
<!-- end: page -->