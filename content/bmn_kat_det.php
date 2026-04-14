<!-- start: page -->
<?php
$id_kat = $_REQUEST["id_kat"] ?? '';
$kat = db_fetch($bp, "SELECT nama_kat FROM kat_bmn WHERE id_kat = :id", ['id' => $id_kat]);
$sections = db_fetch_all($bp, "SELECT * FROM bagian ORDER BY id_seksi");
$jbag = count($sections);
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
                    <i class='fa fa-angle-right'></i> <?= htmlspecialchars($kat['nama_kat'] ?? '') ?>
                </h2>
            </header>
            <div class="card-body">
                <table class="table table-hover table-bordered table-striped mb-0 table-sm" id="tabletools">
                    <thead class="bg-light">
                        <tr>
                            <th class='text-center align-middle' rowspan="2">No</th>
                            <th class='text-center align-middle' rowspan="2">Nama Barang</th>
                            <th class='text-center align-middle' rowspan="2">Jumlah</th>
                            <th class='text-center align-middle' rowspan="2">Digunakan</th>
                            <th class='text-center align-middle' rowspan="2">Sisa</th>
                            <th class='text-center' colspan="<?= $jbag ?>">Pengguna / Penanggung Jawab</th>
                        </tr>
                        <tr>
                            <?php foreach ($sections as $bag): ?>
                                <th class='text-center'><?= htmlspecialchars($bag['bagian']) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
<?php
$b = 1;
$items = db_fetch_all($bp, "SELECT * FROM bmn WHERE kat_bmn = :id_kat", ['id_kat' => $id_kat]);
foreach ($items as $bm):
    $usage = db_fetch($bp, "SELECT SUM(jumlah_pakai) as j_pakai FROM bmn_dist WHERE id_bmn = :id", ['id' => $bm['id_bmn']]);
    $sisa = $bm["jumlah_bmn"] - ($usage['j_pakai'] ?? 0);
?>
                        <tr>
                            <td><?= $b++ ?></td>
                            <td><?= htmlspecialchars($bm['nama_bmn']) ?></td>
                            <td class="text-center"><?= $bm['jumlah_bmn'] ?></td>
                            <td class="text-center"><?= $usage['j_pakai'] ?? 0 ?></td>
                            <td class="text-center"><?= $sisa ?></td>
<?php
            foreach ($sections as $bagp):
                $usage_sec = db_fetch($bp, "SELECT SUM(jumlah_pakai) as jpb FROM bmn_dist WHERE id_bmn = :id AND seksi = :seksi", 
                                      ['id' => $bm['id_bmn'], 'seksi' => $bagp['id_seksi']]);
                $jum = ($usage_sec['jpb'] > 0) ? $usage_sec['jpb'] : "-";
?>
                            <td class='text-center'><?= $jum ?></td>
<?php       endforeach; ?>
                        </tr>
<?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
<!-- end: page -->