<!-- start: page -->
<?php
$nm = htmlspecialchars($_REQUEST["nm"] ?? 'Semua');
$kat_id = $_REQUEST["kat"] ?? '';
$kon = !empty($kat_id) ? "WHERE kat_bmn = :kat" : "";
$params = !empty($kat_id) ? ['kat' => $kat_id] : [];

$query_bmn = db_fetch_all($bp, "SELECT * FROM bmn $kon ORDER BY nama_bmn ASC", $params);
?>
<div class="row">
    <div class="col">
        <section class="card">
            <header class="card-header">
                <div class="card-actions">
                    <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                </div>
                <h2 class="card-title">
                    <a href='?p=bmn_kat'>Data BMN</a> 
                    <i class='fa fa-angle-right'></i> <?= $nm ?>
                </h2>
            </header>
            <div class="card-body">
<?php if (count($query_bmn) > 0): ?>
                <table class="table table-hover table-bordered table-striped mb-0" id="datatable-tabletools">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Keterangan</th>
                            <th>Jumlah</th>
                            <th>Dipakai</th>
                            <th>Belum Terpakai</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
$i = 1;
foreach ($query_bmn as $data):
    $usage = db_fetch($bp, "SELECT SUM(jumlah_pakai) as dipakai, SUM(jumlah_kembali) as kembali FROM bmn_dist WHERE id_bmn = :id", ['id' => $data['id_bmn']]);
    $digunakan = ($usage['dipakai'] ?? 0) - ($usage['kembali'] ?? 0);
    $used = ($digunakan > 0) ? $digunakan : '0';
    $gdg = $data["jumlah_bmn"] - ($usage['dipakai'] ?? 0);
?>
                        <tr class="gradeX">
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($data['nama_bmn']) ?></td>
                            <td><?= htmlspecialchars($data['keterangan']) ?></td>
                            <td><?= $data['jumlah_bmn'] ?> <?= htmlspecialchars($data['satuan']) ?></td>
                            <td><a href='?p=bmn_pakai&id_bmn=<?= htmlspecialchars($data['id_bmn']) ?>&kat=<?= htmlspecialchars($data['kat_bmn']) ?>' class='btn btn-xs btn-success'><?= $used ?></a></td>
                            <td><?= $gdg ?></td>
                            <td width='auto'>
                                <div class='btn-group flex-wrap'>	
                                    <a class='btn btn-xs btn-primary' href='?p=bmn_pakai&id_bmn=<?= htmlspecialchars($data['id_bmn']) ?>&kat=<?= htmlspecialchars($data['kat_bmn']) ?>' title='Detail'><i class='fa fa-search'></i></a>
                                    <a class='simple-ajax-modal btn btn-xs btn-success' href='ajax/bmn_dist.php?id_bmn=<?= htmlspecialchars($data['id_bmn']) ?>&kat=<?= htmlspecialchars($kat_id) ?>&nm=<?= urlencode($nm) ?>' title='Pakai'><i class='fa fa-user'></i></a>
                                    <a class='simple-ajax-modal btn btn-xs btn-warning' href='ajax/bmn_edit.php?d=bmn&id=<?= htmlspecialchars($data['id_bmn']) ?>&kat=<?= htmlspecialchars($data['kat_bmn']) ?>' title='Edit'><i class='fa fa-edit'></i></a>
                                    <a class='simple-ajax-modal btn btn-xs btn-danger' href='ajax/delete.php?d=bmn&id=<?= htmlspecialchars($data['id_bmn']) ?>&nm=<?= urlencode($data['nama_bmn']) ?>' title='Hapus'><i class='fa fa-trash'></i></a>
                                </div>
                            </td>
                        </tr>
<?php endforeach; ?>
<?php
// Footer row for totals
$tot_sql = "SELECT SUM(jumlah_bmn) as total FROM bmn";
$dist_sql = "SELECT SUM(jumlah_pakai) as pakai, SUM(jumlah_kembali) as kembali FROM bmn_dist WHERE id_bmn IN (SELECT id_bmn FROM bmn)";
if (!empty($kat_id)) {
    $tot_sql .= " WHERE kat_bmn = :kat";
    $dist_sql .= " AND id_bmn IN (SELECT id_bmn FROM bmn WHERE kat_bmn = :kat)";
}
$all = db_fetch($bp, $tot_sql, $params);
$usage_all = db_fetch($bp, $dist_sql, $params);
$digunakan_all = ($usage_all['pakai'] ?? 0) - ($usage_all['kembali'] ?? 0);
$sisa_all = ($all['total'] ?? 0) - $digunakan_all;
?>
                        <tr class="gradeX font-weight-bold">
                            <td><?= $i ?></td>
                            <td>Semua</td>
                            <td>Semua Kategori <?= $nm ?></td>
                            <td><?= $all['total'] ?? 0 ?></td>
                            <td><?= $digunakan_all ?></td>
                            <td><?= $sisa_all ?></td>
                            <td><a class='btn btn-xs btn-success' href='?p=bmn_detail'><i class='fa fa-search'></i> Detail</a></td>
                        </tr>
                    </tbody>
                </table>
<?php else: ?>
                <div class="alert alert-warning">
                    DATA KOSONG, SILAHKAN  <a href='ajax/bmn_kat_add.php' class='simple-ajax-modal alert-link text-success'><i class="fa fa-plus"></i> TAMBAHKAN</a> JENIS BMN TERLEBIH DAHULU
                </div>
<?php endif; ?>
            </div>
        </section>
    </div>
</div>
<!-- end: page -->