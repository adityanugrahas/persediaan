<!-- start: page -->
<?php
$id = htmlspecialchars($_REQUEST["id"]);
$a = db_fetch($bp, "SELECT * FROM anggaran WHERE id_anggaran = :id", ['id' => $id]);
?>
<div class="row">
<div class="col">
<section class="card">
    <header class="card-header">
        <div class="card-actions">
            <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
        </div>

        <h2 class="card-title">Detail Anggaran</h2>
    </header>
    <div class="card-body">
        <div class="alert alert-info nomargin">
            <table width="auto" cellspacing="5" cellpadding="5" style="border:0px solid #000">
                <tr><td>Tahun Anggaran</td><td>: <?= htmlspecialchars($a['tahun_anggaran'] ?? '') ?></td></tr>
                <tr><td>Akun</td><td>: <?= htmlspecialchars($a['akun_anggaran'] ?? '') ?></td></tr>
                <tr><td>Keterangan</td><td>: <?= htmlspecialchars($a['ket_anggaran'] ?? '') ?></td></tr>
            </table>
        </div>
        <table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
        <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Kode</th>
            <th>Keterangan</th>
            <th>Penambahan Anggaran</th>
            <th>Pengurangan Anggaran</th>
            <th>Sisa Anggaran</th>
            <th>Action</th>
        </tr>
    </thead>
            <tbody>
            <?php
$i = 0;
$sisa = 0;
$notas = db_fetch_all($bp, "SELECT * FROM nota WHERE id_anggaran = :id ORDER BY id_nota", ['id' => $id]);
foreach ($notas as $data) {
    $i++;
    $sisa += $data["jum_in"];
    $sisa -= $data["jum_out"];
    $jum_in = number_format($data["jum_in"]);
    $jum_out = number_format($data["jum_out"]);
    $sisa2 = number_format($sisa);
?>
    <tr class="gradeX">
        <td><?= $i ?></td>
        <td><?= htmlspecialchars($data['tanggal']) ?></td>
        <td><?= htmlspecialchars($data['kode_nota']) ?></td>
        <td><?= htmlspecialchars($data['keterangan']) ?></td>
        <td><?= $jum_in ?></td>
        <td><?= $jum_out ?></td>
        <td><?= $sisa2 ?></td>
        <td width="auto">
            <a class="simple-ajax-modal modal-with-form btn btn-xs btn-primary" href="ajax/cekin_item.php?d=anggaran&nota=<?= htmlspecialchars($data['id_nota']) ?>"><i class="fa fa-list"></i></a>
            <a class="simple-ajax-modal modal-with-form btn btn-xs btn-success" href="ajax/cekin_lamp.php?d=anggaran&nota=<?= htmlspecialchars($data['id_nota']) ?>"><i class="fa fa-file-image"></i></a>
        </td>
    </tr>
<?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
                     
    <!-- end: page -->