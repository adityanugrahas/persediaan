<!-- start: page -->
<?php
$ta = htmlspecialchars($_REQUEST["ta"]);
?>
<div class="row">
<div class="col">
<section class="card">
    <header class="card-header">
        <div class="card-actions">
            <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
        </div>

        <h2 class="card-title">Anggaran Tahun Anggaran <?= $ta ?></h2>
    </header>
    <div class="card-body">
        <table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
        <thead>
        <tr>
            <th>No</th>
            <th>Akun</th>
            <th>Keterangan</th>
            <th>Pagu</th>
            <th>Serapan</th>
            <th>Sisa</th>
            <th>Action</th>
        </tr>
    </thead>
            <tbody>
            <?php
$i = 0;
$rows = db_fetch_all($bp, "SELECT * FROM anggaran WHERE tahun_anggaran = :ta", ['ta' => $ta]);
foreach ($rows as $data) {
    $i++;
    $sisa = $data["pagu_anggaran"] - $data["serapan_anggaran"];
    $pagu = number_format($data["pagu_anggaran"]);
    $serapan = number_format($data["serapan_anggaran"]);
    $sisa_fmt = number_format($sisa);
    $persen = ($data["pagu_anggaran"] > 0) ? number_format($data["serapan_anggaran"] / $data["pagu_anggaran"] * 100, 2) : "0.00";
?>
    <tr class="gradeX">
        <td><?= $i ?></td>
        <td><?= htmlspecialchars($data['akun_anggaran']) ?></td>
        <td><?= htmlspecialchars($data['ket_anggaran']) ?></td>
        <td><?= $pagu ?></td>
        <td><?= $serapan ?></td>
        <td><?= $sisa_fmt ?></td>
        <td width="auto">
            <a class="simple-ajax-modal modal-with-form btn btn-xs btn-success" href="ajax/anggaran_edit.php?id=<?= htmlspecialchars($data['id_anggaran']) ?>"><i class="fa fa-edit"></i></a>
            <a class="simple-ajax-modal btn btn-xs btn-danger modal-with-form" href="ajax/delete.php?d=anggaran&id=<?= htmlspecialchars($data['id_anggaran']) ?>"><i class="fa fa-trash"></i></a>
            <a class="simple-ajax-modal btn btn-xs btn-primary modal-with-form" href="ajax/pagu_add.php?d=anggaran&id=<?= htmlspecialchars($data['id_anggaran']) ?>"><i class="fa fa-plus"></i></a>
            <a class="btn btn-xs btn-warning" href="?p=anggaran_det&id=<?= htmlspecialchars($data['id_anggaran']) ?>"><i class="fa fa-search"></i></a>
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