<!-- start: page -->
<?php
$periode = htmlspecialchars($_GET["periode"] ?? date("Y"));
?>
<div class="row">
<div class="col">
<section class="card">
    <div class="card-body">
    <div class="alert alert-info nomargin">
            <form action="" method="get" class="logo">
            <input type="hidden" name="p" value="rekap_stok">
            <label class="col-lg-2 control-label pt-2">Periode</label>
            <input type="number" name="periode" maxlength="4" id="periode" pattern="\d{4}" value="<?= $periode ?>" max="<?= date("Y") ?>" required/>
             <input type='submit' class="btn btn-sm btn-primary" value='Tampilkan'>
            </form>
        </div>

        <table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
        <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Jan</th>
            <th>Feb</th>
            <th>Mar</th>
            <th>Apr</th>
            <th>Mei</th>
            <th>Jun</th>
            <th>Jul</th>
            <th>Agust</th>
            <th>Sept</th>
            <th>Okt</th>
            <th>Nov</th>
            <th>Des</th>
            <th><?= $periode ?></th>
        </tr>
    </thead>
            <tbody>
            <?php
$i = 0;
$bagians = db_fetch_all($bp, "SELECT id_seksi, bagian FROM bagian ORDER BY urutan ASC");
foreach ($bagians as $data):
    $i++;
    $jum = 0;
?>
    <tr class="gradeX">
        <td><?= $i ?></td>
        <td><?= htmlspecialchars($data['bagian']) ?></td>
<?php
    for ($b = 1; $b <= 12; $b++):
        $bstr = str_pad($b, 2, '0', STR_PAD_LEFT);
        $jb = (int)db_fetch_column($bp, "SELECT COUNT(*) FROM stok_inout WHERE id_seksi = :seksi AND jenis = 'out' AND status = '2' AND tgl_ok::text LIKE :period",
                                   ['seksi' => $data['id_seksi'], 'period' => $periode . '-' . $bstr . '%']);
        $jum += $jb;
        
        if ($jb > 0) {
            $cell = "<a href='?p=rekap&f=pakai&bag=" . htmlspecialchars($data['id_seksi']) . "&periode=" . $periode . "-" . $bstr . "'>" . $jb . "</a>";
        } else {
            $cell = "<span class='text-danger'>" . $jb . "</span>";
        }
?>
        <td><?= $cell ?></td>
<?php endfor; ?>
        <td><a href='?p=rekap&f=pakai&bag=<?= htmlspecialchars($data['id_seksi']) ?>&periode=<?= $periode ?>'><?= $jum ?></a></td>
    </tr>
<?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
                     
    <!-- end: page -->