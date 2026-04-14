<!-- start: page -->
<script>
function popUp(URL) {
    const day = new Date();
    const id = day.getTime();
    window.open(URL, id, 'toolbar=0,scrollbars=1,location=0,width=800,height=1000,statusbar=0,menubar=0,resizable=0,left=300,top=100');
}
</script>
<?php
$id_petugas = $_REQUEST["id"] ?? '';
$p = db_fetch($bp, "SELECT p.*, b.bagian FROM petugas p LEFT JOIN bagian b ON p.seksi = b.id_seksi WHERE p.id_petugas = :id", ['id' => $id_petugas]);

if (!$p) {
    echo "Petugas tidak ditemukan.";
    return;
}
?>
<div class="row">
    <div class="col">
        <section class="card">
            <header class="card-header">
                <div class="card-actions">
                    <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                </div>
                <h2 class="card-title">
                    <a href='?p=bmn_pemegang'>Data Penanggung Jawab</a> <i class='fa fa-angle-right'></i> Detail Aset <?= htmlspecialchars($p['nama']) ?>
                </h2>
            </header>
            <div class="card-body">
                <div class="alert alert-info nomargin">
                    <table class="table table-sm table-borderless mb-0">
                        <tr><td width="100">Nama</td><td>: <?= htmlspecialchars($p['nama']) ?></td></tr>
                        <tr><td>NIP/ID</td><td>: <?= htmlspecialchars($p['users_id']) ?></td></tr>
                        <tr><td>Bagian/Seksi</td><td>: <?= htmlspecialchars($p['bagian'] ?? '-') ?></td></tr>
                    </table>
                </div>
                <table class="table table-hover table-bordered table-striped mb-0 mt-3" id="datatable-tabletools">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Barang</th>
                            <th>Tanggal</th>
                            <th>Seksi</th>
                            <th>Jumlah Pakai</th>
                            <th>Jumlah Kembali</th>
                            <th>Keterangan</th>
                            <th>Lampiran</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
$items = db_fetch_all($bp, "SELECT d.*, b.nama_bmn, b.satuan, s.bagian as seksi_dist 
                             FROM bmn_dist d 
                             JOIN bmn b ON d.id_bmn = b.id_bmn 
                             LEFT JOIN bagian s ON d.seksi = s.id_seksi
                             WHERE d.pengguna = :id 
                             ORDER BY d.tgl_dist DESC", ['id' => $id_petugas]);

$i = 1;
$total = 0;
$total_kembali = 0;
foreach ($items as $d):
    $style = ($d["jumlah_kembali"] > 0) ? "class='table-secondary'" : "";
    $jp = ($d["jumlah_pakai"] > 0) ? $d["jumlah_pakai"] . " " . $d["satuan"] : "";
    $jk = ($d["jumlah_kembali"] > 0) ? $d["jumlah_kembali"] . " " . $d["satuan"] : "";
?>
                        <tr <?= $style ?>>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($d['nama_bmn']) ?></td>
                            <td><?= htmlspecialchars($d['tgl_dist']) ?></td>
                            <td><?= htmlspecialchars($d['seksi_dist'] ?? '-') ?></td>
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
                                    <a class='simple-ajax-modal btn btn-xs btn-warning' href='ajax/bmn_kembali.php?id_dist=<?= htmlspecialchars($d['id_dist']) ?>' title="Kembalikan"><i class='fa fa-reply'></i></a>
                                    <?php endif; ?>
                                    <a class='simple-ajax-modal btn btn-xs btn-danger' href='ajax/delete.php?d=bmn_dist&id_dist=<?= htmlspecialchars($d['id_dist']) ?>' title="Hapus"><i class='fa fa-trash'></i></a>
                                </div>
                            </td>
                        </tr>
<?php
    $total += $d["jumlah_pakai"];
    $total_kembali += $d["jumlah_kembali"];
endforeach;
?>
                    </tbody>
                    <tfoot class="bg-light font-weight-bold">
                        <tr>
                            <th colspan="4" class="text-center">JUMLAH</th>
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
