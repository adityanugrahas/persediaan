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
$f = htmlspecialchars($_REQUEST["f"] ?? '');
$arus = "";
$st = "Pemakaian dan Penambahan Stok Barang";
$th = "<th>Penambahan</th><th>Pemakaian</th>";

if ($f === "pakai") {
    $st = "Pemakaian";
    $arus = "and jenis='out'";
    $th = "<th>Pemakaian</th>";
} elseif ($f === "masuk") {
    $st = "Penambahan Jumlah Stok";
    $arus = "and jenis='in'";
    $th = "<th>Penambahan</th>";
} elseif ($f === "semua") {
    // defaults are fine
}

$kon_level = "";
$params = [];
if ($_SESSION["levelp"] === "opr") {
    $kon_level = "AND petugas = :ptg";
    $params['ptg'] = $_SESSION['idp'];
}

$periode = htmlspecialchars($_GET["periode"] ?? date("Y-m"));
?>
<h2 class="card-title">Rekapitulasi <?= $st ?> Barang</h2>
    </header>
    <div class="card-body">
    <div class="alert alert-info nomargin">
            <form action="" method="get" class="logo">
            <input type="hidden" name="p" value="rekap">
            <input type="hidden" name="f" value="<?= $f ?>">
            <label class="col-lg-1 control-label pt-2">Periode</label>
                <input type="month" name='periode' value="<?= $periode ?>">
             <input type='submit' class="btn btn-sm btn-primary" value='Tampilkan'>
            </form>
        </div>
    <?php
// Build parameterized query
$sql = "SELECT * FROM stok_inout WHERE status = '2'";

if ($f === "pakai") {
    $sql .= " AND jenis = 'out'";
} elseif ($f === "masuk") {
    $sql .= " AND jenis = 'in'";
}

if ($_SESSION["levelp"] === "opr") {
    $sql .= " AND petugas = :ptg";
    $params['ptg'] = $_SESSION['idp'];
}

$sql .= " AND tgl LIKE :periode ORDER BY tgl ASC";
$params['periode'] = $periode . '%';

$rows = db_fetch_all($bp, $sql, $params);
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
            <?= $th ?>
            <th>Saldo </th>
            <th>Satuan</th>
            <th>Keterangan</th>
        </tr>
    </thead>
            <tbody>
<?php foreach ($rows as $data):
    $i++;
    $brg = db_fetch($bp, "SELECT nama_barang, gambar FROM stok_barang WHERE id_barang = :id", ['id' => $data['id_barang']]);
    $ptg = db_fetch($bp, "SELECT nama FROM petugas WHERE id_petugas = :id", ['id' => $data['petugas']]);
    $sek_row = db_fetch($bp, "SELECT bagian FROM bagian WHERE id_seksi = :id", ['id' => $data['id_seksi']]);
    
    $td = "";
    if ($f === "pakai") {
        $td = "<td>" . $data['jml_out'] . "</td>";
    } elseif ($f === "masuk") {
        $td = "<td>" . $data['jml_in'] . "</td>";
    } else {
        $td = "<td>" . $data['jml_in'] . "</td><td>" . $data['jml_out'] . "</td>";
    }
?>
    <tr class="gradeX">
        <td><?= $i ?></td>
        <td><?= htmlspecialchars($data['tgl']) ?></td>
        <td><?= htmlspecialchars($ptg['nama'] ?? '') ?> - <?= htmlspecialchars($sek_row['bagian'] ?? '') ?></td>
        <td><?= htmlspecialchars($brg['nama_barang'] ?? '') ?></td>
        <?= $td ?>
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