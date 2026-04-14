<!-- start: page -->
<SCRIPT LANGUAGE="JavaScript">
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,width=800,height=1000,statusbar=0,menubar=0,resizable=0,left = 300,top = 100');");
}
</script>

<div class='text-right mr-4'>
<a class='simple-ajax-modal btn btn-xs btn-danger modal-with-form btn btn-xs btn-primary' href='ajax/anggaran_add.php'><i class='fa fa-plus'></i> Tambah Data Anggaran</a>
</div><hr>
<?php
$stmt = $bp->query("SELECT DISTINCT tahun_anggaran, status FROM anggaran ORDER BY tahun_anggaran DESC");
$years = $stmt->fetchAll();
$i = 0;

foreach ($years as $data):
    $i++;
    $color = ($data["status"] == "0") ? "#787D88" : "#fff";
    $status_text = ($data["status"] == "0") ? "Tidak Aktif" : "Aktif";
    
    if ($data["status"] == "0") {
        $stat_action = "<a href='?p=update&tab=anggaran_set&ta={$data['tahun_anggaran']}&set=ok' class='btn btn-xs btn-primary'><i class='fa fa-check'></i> Aktifkan</a>";
        $del_action = "<a class='simple-ajax-modal btn btn-xs btn-danger' href='ajax/delete.php?d=anggaran_ta&ta={$data['tahun_anggaran']}'><i class='fa fa-trash'></i> Hapus</a>";
    } else {
        $stat_action = "<a href='?p=update&tab=anggaran_set&ta={$data['tahun_anggaran']}&set=no' class='btn btn-xs btn-warning'><i class='fa fa-ban'></i> Non Aktifkan</a>";
        $del_action = "";
    }
?>
<div class='row'>
    <div class='col-12'>
        <div class='card mb-4'>
            <div class='card-body' style='background-color: <?= $color ?>'>
                <div class='widget-summary'>
                    <div class='widget-summary-col'>
                        <div class='summary'>
                            <table class='table table-sm mb-0'>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>T.A</th>
                                        <th>Detail Anggaran</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class='gradeX'>
                                        <td><?= $i ?></td>
                                        <td><?= htmlspecialchars($data['tahun_anggaran']) ?></td>
                                        <td>
                                            <table width='100%' class='table table-hover table-bordered'>
                                                <thead>
                                                    <tr>
                                                        <th>Akun</th>
                                                        <th>Keterangan</th>
                                                        <th>Pagu</th>
                                                        <th>Serapan</th>
                                                        <th>Sisa</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $stmt_ag = $bp->prepare("SELECT * FROM anggaran WHERE tahun_anggaran = :ta");
                                                    $stmt_ag->execute(['ta' => $data['tahun_anggaran']]);
                                                    while ($ag = $stmt_ag->fetch()):
                                                        $sisa = $ag["pagu_anggaran"] - $ag["serapan_anggaran"];
                                                        $pagu = number_format($ag["pagu_anggaran"]);
                                                        $serapan = number_format($ag["serapan_anggaran"]);
                                                        $sisa_fmt = number_format($sisa);
                                                        $persen = ($ag["pagu_anggaran"] > 0) ? ($ag["serapan_anggaran"] / $ag["pagu_anggaran"] * 100) : 0;
                                                        $persen_fmt = number_format($persen, 2);
                                                    ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($ag['akun_anggaran']) ?></td>
                                                            <td><?= htmlspecialchars($ag['ket_anggaran']) ?></td>
                                                            <td align='right'><?= $pagu ?></td>
                                                            <td align='right'><?= $serapan ?> - <span class='badge badge-info'><?= $persen_fmt ?>%</span></td>
                                                            <td align='right'><?= $sisa_fmt ?></td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td><?= $status_text ?></td>
                                        <td>
                                            <div class="btn-group-vertical">
                                                <?= $stat_action ?>
                                                <a href='?p=anggaran_ta&ta=<?= $data['tahun_anggaran'] ?>' class='btn btn-xs btn-success'><i class='fa fa-search'></i> Detail</a>
                                                <?= $del_action ?>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- end: page -->