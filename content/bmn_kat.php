<!-- start: page -->
<div class="row">
    <div class="col">
        <section class="card">
            <header class="card-header">
                <div class="card-actions">
                    <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                </div>
                <h2 class="card-title">Data BMN</h2>
            </header>
            <div class="card-body">
<?php
$categories = db_fetch_all($bp, "SELECT * FROM kat_bmn ORDER BY id_kat ASC");
if (count($categories) > 0):
?>
                <table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Kategori</th>
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
foreach ($categories as $data):
    $stock = db_fetch($bp, "SELECT SUM(jumlah_bmn) as gudang, MAX(satuan) as satuan FROM bmn WHERE kat_bmn = :kat", ['kat' => $data['id_kat']]);
    $usage = db_fetch($bp, "SELECT SUM(jumlah_pakai) as pakai, SUM(jumlah_kembali) as kembali FROM bmn_dist WHERE id_bmn IN (SELECT id_bmn FROM bmn WHERE kat_bmn = :kat)", ['kat' => $data['id_kat']]);
    
    $digunakan = ($usage['pakai'] ?? 0) - ($usage['kembali'] ?? 0);
    $sisa = ($stock['gudang'] ?? 0) - $digunakan;
?>
                        <tr class="gradeX">
                            <td><?= $i++ ?></td>
                            <td><a href='?p=bmn&kat=<?= htmlspecialchars($data['id_kat']) ?>&nm=<?= urlencode($data['nama_kat']) ?>' class='btn btn-xs btn-primary'><?= htmlspecialchars($data['nama_kat']) ?></a></td>
                            <td><?= htmlspecialchars($data['ket_kat']) ?></td>
                            <td><?= $stock['gudang'] ?? 0 ?> <?= htmlspecialchars($stock['satuan'] ?? '') ?></td>
                            <td><a href='?p=bmn_kat_pakai&kat=<?= htmlspecialchars($data['id_kat']) ?>' class='btn btn-xs btn-success'><?= $digunakan ?></a></td>
                            <td><?= $sisa ?></td>
                            <td width='auto'>
                                <div class='btn-group flex-wrap'>			
                                    <a class='btn btn-xs btn-info' href='?p=bmn&kat=<?= htmlspecialchars($data['id_kat']) ?>&nm=<?= urlencode($data['nama_kat']) ?>' title='Data BMN'><i class='fa fa-list'></i></a>
                                    <a class='btn btn-xs btn-success' href='?p=bmn_kat_det&id_kat=<?= htmlspecialchars($data['id_kat']) ?>' title='Detail'><i class='fa fa-search'></i></a>
                                    <a class='simple-ajax-modal btn btn-xs btn-primary' href='ajax/bmn_add.php?kat_bmn=<?= htmlspecialchars($data['id_kat']) ?>' title='Tambah BMN'><i class='fa fa-plus'></i></a>
                                    <a class='simple-ajax-modal btn btn-xs btn-warning' href='ajax/bmn_kat_edit.php?id=<?= htmlspecialchars($data['id_kat']) ?>' title='Edit Kategori'><i class='fa fa-edit'></i></a>
                                    <a class='simple-ajax-modal btn btn-xs btn-danger' href='ajax/delete.php?d=kat_bmn&id=<?= htmlspecialchars($data['id_kat']) ?>&nm=<?= urlencode($data['nama_kat']) ?>' title='Hapus'><i class='fa fa-trash'></i></a>
                                </div>
                            </td>
                        </tr>
<?php endforeach; ?>
<?php
$all_stock = db_fetch($bp, "SELECT SUM(jumlah_bmn) as total FROM bmn");
$all_usage = db_fetch($bp, "SELECT SUM(jumlah_pakai) as pakai, SUM(jumlah_kembali) as kembali FROM bmn_dist");
$digunakan_all = ($all_usage['pakai'] ?? 0) - ($all_usage['kembali'] ?? 0);
$sisa_all = ($all_stock['total'] ?? 0) - $digunakan_all;
?>
                        <tr class="gradeX font-weight-bold">
                            <td><?= $i ?></td>
                            <td><a href='?p=bmn' class='btn btn-xs btn-primary'>Semua</a></td>
                            <td>Semua BMN</td>
                            <td><?= $all_stock['total'] ?? 0 ?></td>
                            <td><?= $digunakan_all ?></td>
                            <td><?= $sisa_all ?></td>
                            <td><a class='btn btn-xs btn-success' href='?p=bmn_detail' title='Detail'><i class='fa fa-search'></i> Detail</a></td>
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