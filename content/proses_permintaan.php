<?php
$tanggal = date("Ymd");
$sek = db_fetch($bp, "SELECT bagian FROM bagian WHERE id_seksi = :seksi", ['seksi' => $_SESSION['seksip']]);
?>

    <!-- start: page -->

    <section class="card">
        <div class="card-body">
                <header class="clearfix">
                    <div class="row">
                        <div class="col-sm-6 mt-0">
                            <h5 class="h5 mt-0 mb-1 text-dark font-weight-bold">PERMOHONAN PEMAKAIAN BARANG</h5>
                        </div>
                    </div>
                </header>
<!--START DATA PERMINTAAN-->
        </div>
        
    </section>
<hr>
<div class="col-lg-12">
								<section class="card">
									<header class="card-header card-header-danger">
										<div class="card-actions">
											<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
										</div>
<?php
$i = 1;
$items = db_fetch_all($bp, "SELECT i.id_inout, i.id_barang, i.jml_req, i.jml_out, i.satuan, i.keterangan, b.nama_barang 
                            FROM stok_inout i JOIN stok_barang b ON i.id_barang = b.id_barang 
                            WHERE i.jenis = 'out' AND i.status = '1' AND i.id_seksi = :seksi",
                      ['seksi' => $_SESSION['seksip']]);
$jreq = count($items);
?>
										<h2 class="card-title">Petugas Bidang</h2>
									</header>
									<div class="card-body">
										<table class="table table-bordered table-sm mb-0">
                    <thead>
                        <tr class="text-dark">
                            <th class="text-center font-weight-semibold">No</th>
                            <th class="text-center font-weight-semibold">Nama Barang</th>
                            <th class="text-center font-weight-semibold">Jumlah Permintaan</th>
                            <th class="text-center font-weight-semibold">Jumlah Disetujui</th>
                            <th class="text-center font-weight-semibold">Satuan</th>
                            <th class="text-center font-weight-semibold">Keterangan</th>
                            <th class="text-center font-weight-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php foreach ($items as $req): ?>
        <tr class='text-dark'>
            <td class='text-center font-weight-semibold'><?= $i ?></td>
            <td class='text-center font-weight-semibold'><?= htmlspecialchars($req['nama_barang']) ?></td>
            <td class='text-center font-weight-semibold'><?= $req['jml_req'] ?></td>
            <td class='text-center font-weight-semibold'><?= $req['jml_out'] ?></td>
            <td class='text-center font-weight-semibold'><?= htmlspecialchars($req['satuan']) ?></td>
            <td class='text-center font-weight-semibold'><?= htmlspecialchars($req['keterangan']) ?></td>
            <td class='text-center font-weight-semibold'>
                <a href='ajax/delete.php?d=cart&id=<?= htmlspecialchars($req['id_inout']) ?>' class='simple-ajax-modal btn btn-xs btn-danger' title='Hapus'><i class='fa fa-trash'></i></a>
                <a href='ajax/delete.php?d=cart&id=<?= htmlspecialchars($req['id_inout']) ?>' class='simple-ajax-modal btn btn-xs btn-warning' title='Edit'><i class='fa fa-edit'></i></a>
            </td>
        </tr>
    <?php $i++; endforeach; ?>
<tr><td colspan="7" class="text-right"><a href="?p=acc_permintaan" class="text-right btn btn-sm btn-primary"><i class="fa fa-check"></i> Setujui </a></td></tr>
</tbody>
										</table>
									</div>
								</section>
							</div>
    <!-- end: page -->