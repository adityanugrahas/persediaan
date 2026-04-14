<!-- start: page -->
<?php
$periode = htmlspecialchars($_GET["periode"] ?? date("Y"));
?>
<div class="row">
<div class="col">
<section class="card">
    <header class="card-header">
        <div class="card-actions">
            <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
        </div>

        <h2 class="card-title">Rekapitulasi Stok</h2>
    </header>
    <div class="card-body">
    <div class="alert alert-info nomargin">
            <form action="" method="get" class="logo">
            <input type="hidden" name="p" value="rekap_stok">
            <label class="col-lg-2 control-label pt-2">Periode</label>
            <input type="number" name="periode" maxlength="4" id="periode" pattern="\d{4}" value="<?= $periode ?>" max="<?= date("Y") ?>" required/>
             <input type='submit' class="btn btn-sm btn-primary" value='Tampilkan'>
            </form>
        </div>

        <table class="table table-hover table-bordered table-striped mb-0" id="datatable-tabletools">
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
        </tr>
    </thead>
            <tbody>
            <?php
$i = 0;
$periodeskrg = date("Y-m");
$items = db_fetch_all($bp, "SELECT id_barang, nama_barang, jumlah_stok FROM stok_barang WHERE aktif = 'ya' ORDER BY nama_barang ASC");
foreach ($items as $data):
    $i++;
?>
    <tr class="gradeX">
        <td><?= $i ?></td>
        <td><a href='?p=stok_mutasi&id_barang=<?= htmlspecialchars($data['id_barang']) ?>&periode=<?= $periode ?>&nm_brg=<?= urlencode($data['nama_barang']) ?>'><?= htmlspecialchars($data['nama_barang']) ?></a></td>
<?php
    for ($b = 1; $b <= 12; $b++):
        $bstr = str_pad($b, 2, '0', STR_PAD_LEFT);
        $jbr = db_fetch($bp, "SELECT jml_stok FROM stok_inout WHERE id_barang = :id AND tgl_ok::text LIKE :period ORDER BY tgl_ok DESC LIMIT 1",
                        ['id' => $data['id_barang'], 'period' => $periode . '-' . $bstr . '%']);
        
        if (!empty($jbr['jml_stok'])) {
            $cell = "<a href='?p=stok_mutasi&id_barang=" . htmlspecialchars($data['id_barang']) . "&periode=" . $periode . "-" . $bstr . "&nm_brg=" . urlencode($data['nama_barang']) . "'>" . $jbr['jml_stok'] . "</a>";
        } elseif ($periode . "-" . $bstr === $periodeskrg) {
            $cell = "<a href='?p=stok_mutasi&id_barang=" . htmlspecialchars($data['id_barang']) . "&periode=" . $periode . "-" . $bstr . "&nm_brg=" . urlencode($data['nama_barang']) . "'>" . $data['jumlah_stok'] . "</a>";
        } else {
            $cell = "";
        }
?>
        <td><?= $cell ?></td>
<?php endfor; ?>
    </tr>
<?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
                     
    <!-- end: page -->