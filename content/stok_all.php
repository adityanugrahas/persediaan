<?php
$k = htmlspecialchars($_GET["k"] ?? '');
$header = "Stok Barang";
$params = [];
$sql = "SELECT * FROM stok_barang WHERE aktif = 'ya'";

if (!empty($k)) {
    $header = $k;
    $sql .= " AND (nama_barang LIKE :search OR keterangan LIKE :search)";
    $params['search'] = '%' . $k . '%';
}

if (!empty($_REQUEST["kat"])) {
    $kat_id = htmlspecialchars($_REQUEST["kat"]);
    $sql .= " AND kategori = :kat";
    $params['kat'] = $kat_id;
}

$sql .= " ORDER BY nama_barang";
$items = db_fetch_all($bp, $sql, $params);
?>
<!-- start: page -->
<div class="row">
    <div class="col">
        <section class="card">
            
            <div class="card-body">
                <table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Photo</th>
                            <th>Kategori</th>
                            <th>Nama Barang</th>
                            <th>Jumlah Stok</th>
                            <th>Jumlah Minimum</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
$i = 1;
foreach ($items as $br):
    $link = ($br["jumlah_stok"] > 0) ? "Pilih" : "Pesan";
    $warna = ($br["jumlah_stok"] > 0) ? "primary" : "danger";
    $form = ($br["jumlah_stok"] > 0) ? "order.php" : "pesan.php";
    $barang = (!empty($br["gambar"]) && file_exists("img/barang/" . $br["gambar"])) ? $br["gambar"] : "barang.png";
    $kat_row = db_fetch($bp, "SELECT nama_kat FROM kategori WHERE id_kat = :id", ['id' => $br['kategori']]);
    $kategori = !empty($kat_row['nama_kat']) ? $kat_row['nama_kat'] : "Edit Kategori";
    $warnakt = !empty($kat_row['nama_kat']) ? "default" : "dark";
?>
    <td>
        <div class="d-flex align-items-center">
            <figure class='profile-picture mr-3 mb-0'>
                <img src='img/barang/<?= htmlspecialchars($barang) ?>' height='45' width='45' alt='<?= htmlspecialchars($br['nama_barang']) ?>' class='rounded-circle' style="border: 2px solid var(--glass-border); padding: 2px;" />
            </figure>
            <div>
                <span class="font-weight-bold text-white"><?= htmlspecialchars($br['nama_barang']) ?></span><br>
                <small class="text-primary"><?= htmlspecialchars($br['keterangan']) ?></small>
            </div>
        </div>
    </td>
    <td><span class="badge badge-info"><?= $br['jumlah_stok'] ?> <?= htmlspecialchars($br['satuan']) ?></span></td>
    <td><span class="badge badge-warning"><?= $br['stok_minimal'] ?></span></td>
    <td class="text-right">
        <div class="btn-group btn-group-sm">
            <a class="simple-ajax-modal btn btn-<?= $warna ?>" href="ajax/<?= $form ?>?id=<?= htmlspecialchars($br['id_barang']) ?>&k=urlencode($k)">
                <i class="fas fa-shopping-basket mr-1"></i> <?= $link ?>
            </a>
            <?php if ($_SESSION["levelp"] === "su"): ?>
            <button type="button" class="btn btn-<?= $warna ?> dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="?p=stok_edit&id=<?= htmlspecialchars($br['id_barang']) ?>"><i class="fas fa-search-plus mr-2"></i> Detail Barang</a>
                <a class="dropdown-item simple-ajax-modal" href="ajax/stok_add.php?id=<?= htmlspecialchars($br['id_barang']) ?>"><i class="fas fa-plus mr-2"></i> Increase Stock</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item simple-ajax-modal text-danger" href="ajax/stok_delete.php?id=<?= htmlspecialchars($br['id_barang']) ?>"><i class="far fa-trash-alt mr-2"></i> Delete Item</a>
            </div>
            <?php endif; ?>
        </div>
    </td>
</tr>

<?php $i++; endforeach; ?>                      
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>

<!-- end: page -->
