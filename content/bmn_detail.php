<!-- start: page -->
<?php
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
                <h2 class="card-title"><a href='?p=bmn_kat'>Data BMN</a> <i class='fa fa-angle-right'></i> Detail Matrix</h2>
            </header>
            <div class="card-body">
                <table class="table table-hover table-bordered table-striped mb-0 table-sm" id="datatable-tabletools">
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
$categories = db_fetch_all($bp, "SELECT * FROM kat_bmn ORDER BY id_kat ASC");
$k = 1;
foreach ($categories as $kat):
?>
                        <tr class="table-info">
                            <td><b><?= $k ?></b></td>
                            <td colspan="<?= $jbag + 4 ?>"><b><?= htmlspecialchars($kat['nama_kat']) ?></b></td>
                        </tr>
<?php
        $b = 1;
        $items = db_fetch_all($bp, "SELECT * FROM bmn WHERE kat_bmn = :kat_id", ['kat_id' => $kat['id_kat']]);
        foreach ($items as $bm):
            $usage_data = db_fetch($bp, "SELECT SUM(jumlah_pakai) as j_pakai, SUM(jumlah_kembali) as j_kembali FROM bmn_dist WHERE id_bmn = :id", ['id' => $bm['id_bmn']]);
            $dipakai = ($usage_data['j_pakai'] ?? 0) - ($usage_data['j_kembali'] ?? 0);
            $sisa = $bm["jumlah_bmn"] - ($usage_data['j_pakai'] ?? 0);
?>
                        <tr>
                            <td><?= "$k.$b" ?></td>
                            <td> - <?= htmlspecialchars($bm['nama_bmn']) ?></td>
                            <td class='text-center'><?= $bm['jumlah_bmn'] ?> <?= htmlspecialchars($bm['satuan']) ?></td>
                            <td class='text-center'><a href='?p=bmn_pakai&id_bmn=<?= htmlspecialchars($bm['id_bmn']) ?>'><?= $usage_data['j_pakai'] ?? 0 ?></a></td>
                            <td class='text-center'><?= $sisa ?></td>
<?php
            foreach ($sections as $bagp):
                $usage_sec = db_fetch($bp, "SELECT SUM(jumlah_pakai) as jpb, SUM(jumlah_kembali) as jkb FROM bmn_dist WHERE id_bmn = :id AND seksi = :seksi", 
                                      ['id' => $bm['id_bmn'], 'seksi' => $bagp['id_seksi']]);
                $dipakai_sec = ($usage_sec['jpb'] ?? 0) - ($usage_sec['jkb'] ?? 0);
                $jum = ($usage_sec['jpb'] > 0) ? $dipakai_sec : "-";
?>
                            <td class='text-center'><a href='?p=bmn_pakai&id_bmn=<?= htmlspecialchars($bm['id_bmn']) ?>&id_seksi=<?= htmlspecialchars($bagp['id_seksi']) ?>'><?= $jum ?></a></td>
<?php       endforeach; ?>
                        </tr>
<?php       $b++;
        endforeach;
        $k++;
endforeach; 
?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
<!-- end: page -->