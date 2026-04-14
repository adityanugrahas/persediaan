<?php
$k = $_GET["k"] ?? '';
$kat_id = $_REQUEST["kat"] ?? '';
$params = [];
$sql = "SELECT * FROM stok_barang WHERE aktif='ya'";

if (!empty($k)) {
    $sql .= " AND (nama_barang LIKE :k OR keterangan LIKE :k)";
    $params['k'] = '%' . $k . '%';
}

if (!empty($kat_id)) {
    $sql .= " AND kategori = :kat";
    $params['kat'] = $kat_id;
}

$sql .= " ORDER BY nama_barang LIMIT 50";
$items = db_fetch_all($bp, $sql, $params);
?>
<!-- start: page -->
<section class="media-gallery">
    <div class="inner-body mg-main">
        <div class="row col-12 mg-files" data-sort-destination data-sort-id="media-gallery">
<?php
foreach ($items as $br):
    $link = ($br["jumlah_stok"] > 0) ? "Pilih" : "Pesan";
    $warna = ($br["jumlah_stok"] > 0) ? "dark" : "danger";
    $form = ($br["jumlah_stok"] > 0) ? "order.php" : "pesan.php";
    $barang = (!empty($br["gambar"]) && file_exists("img/barang/" . $br["gambar"])) ? $br["gambar"] : "barang.png";
?>
            <div class="isotope-item photos col-sm-2 col-md-2 col-lg-2">
                <div class="thumbnail shadow-sm">
                    <div class="thumb-preview">
                        <a class="thumb-image" href="img/barang/<?= htmlspecialchars($barang) ?>">
                            <img src="img/barang/<?= htmlspecialchars($barang) ?>" class="img-fluid" alt="Barang Image">
                        </a>
                        <div class="mg-thumb-options">
                            <div class="mg-zoom"><i class="fas fa-search"></i></div>
                            <div class="mg-toolbar">
                                <div class="mg-group float-right">
                                    <a class="simple-ajax-modal btn btn-<?= $warna ?> btn-xs" href="ajax/<?= $form ?>?id=<?= htmlspecialchars($br['id_barang']) ?>&k=<?= urlencode($k) ?>"><?= $link ?></a>
                                    <?php if ($_SESSION["levelp"] === "su"): ?>
                                    <button class="dropdown-toggle mg-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                    <div class="dropdown-menu mg-dropdown" role="menu">
                                        <a class="dropdown-item text-1" href="?p=stok_edit&id=<?= htmlspecialchars($br['id_barang']) ?>"><i class="fas fa-search"></i> Detail</a>
                                        <a class="dropdown-item text-1 simple-ajax-modal" href="ajax/stok_delete.php?id=<?= htmlspecialchars($br['id_barang']) ?>"><i class="far fa-trash-alt"></i> Hapus</a>
                                        <a class="dropdown-item text-1 simple-ajax-modal" href="ajax/stok_add.php?id=<?= htmlspecialchars($br['id_barang']) ?>"><i class="fas fa-plus"></i> Tambah Stok</a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="simple-ajax-modal" href="ajax/<?= $form ?>?id=<?= htmlspecialchars($br['id_barang']) ?>&k=<?= urlencode($k) ?>">
                        <h5 class="mg-title font-weight-semibold"><?= htmlspecialchars($br['nama_barang']) ?></h5>
                    </a>
                    <div class="mg-description">
                        <small class="float-left text-muted"><?= $br['jumlah_stok'] ?> <?= htmlspecialchars($br['satuan']) ?></small>
                        <small class="float-right text-muted"><?= htmlspecialchars($br['keterangan']) ?></small>
                    </div>
                </div>                    
            </div>
<?php endforeach; ?>
        </div>
    </div>
</section>
<!-- end: page -->