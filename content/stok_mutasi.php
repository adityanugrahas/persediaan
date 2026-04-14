<!-- start: page -->

<div class="row">
<div class="col">
<section class="card">
    <header class="card-header">
        <div class="card-actions">
            <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
        </div>
<?php
$i = 0;
$id_barang = htmlspecialchars($_REQUEST["id_barang"] ?? '');
$periode = htmlspecialchars($_REQUEST["periode"] ?? date("Y-m"));
$nm_brg = htmlspecialchars($_REQUEST["nm_brg"] ?? '');
?>
<h2 class="card-title">Mutasi Barang <?= $nm_brg ?></h2>
    </header>
    <div class="card-body">
        <div class="alert alert-info nomargin">
            <form action="" method="get" class="logo">
            <input type="hidden" name="p" value="stok_mutasi">
            <input type="hidden" name="id_barang" value="<?= $id_barang ?>">
            <input type="hidden" name="nm_brg" value="<?= $nm_brg ?>">
            <label class="col-lg-2 control-label pt-2">Periode</label>
                <input type="month" name='periode' value="<?= $periode ?>">
                
             <input type='submit' class="btn btn-sm btn-primary" value='Tampilkan'>
            </form>
        </div>
    <?php
$rows = db_fetch_all($bp, "SELECT * FROM stok_inout WHERE status = '2' AND id_barang = :id AND tgl_ok LIKE :periode ORDER BY tgl ASC",
                     ['id' => $id_barang, 'periode' => $periode . '%']);
$jum = count($rows);
if ($jum > 0):
?>
        
        <table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
        <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Pemohon</th>
            <th>Nama Barang</th>
            <th>Penambahan</th>
            <th>Pemakaian</th>
            <th>Saldo </th>
            <th>Satuan</th>
            <th>Keterangan</th>
        </tr>
    </thead>
            <tbody>
<?php foreach ($rows as $data):
    $i++;
    $brg = db_fetch($bp, "SELECT nama_barang FROM stok_barang WHERE id_barang = :id", ['id' => $data['id_barang']]);
    $ptg = db_fetch($bp, "SELECT nama FROM petugas WHERE id_petugas = :id", ['id' => $data['petugas']]);
    $sek_r = db_fetch($bp, "SELECT bagian FROM bagian WHERE id_seksi = :id", ['id' => $data['id_seksi']]);
?>
    <tr class="gradeX">
        <td><?= $i ?></td>
        <td><?= htmlspecialchars($data['tgl']) ?></td>
        <td><?= htmlspecialchars($ptg['nama'] ?? '') ?> - <?= htmlspecialchars($sek_r['bagian'] ?? '') ?></td>
        <td><?= htmlspecialchars($brg['nama_barang'] ?? '') ?></td>
        <td><?= $data['jml_in'] ?></td>
        <td><?= $data['jml_out'] ?></td>
        <td><?= $data['jml_stok'] ?></td>
        <td><?= htmlspecialchars($data['satuan']) ?></td>
        <td><?= htmlspecialchars($data['keterangan']) ?></td>
    </tr>
<?php endforeach; ?>
                        </tbody>
                    </table>
<?php else: ?>
    <div class="alert alert-warning mt-3"><i class="fas fa-inbox mr-1"></i> Data masih kosong.</div>
<?php endif; ?>
                </div>
            </section>
        </div>
    </div>
                     
    <!-- end: page -->