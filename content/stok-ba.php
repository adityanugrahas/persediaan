<?php
$kunci = $_POST["kunci"] ?? '';
$header = $kunci ?: "Stok Barang";
$params = [];
$sql = "SELECT * FROM stok_barang";

if (!empty($kunci)) {
    $sql .= " WHERE nama_barang ILIKE :kunci OR keterangan ILIKE :kunci";
    $params['kunci'] = '%' . $kunci . '%';
}

if (!empty($_REQUEST["kat"])) {
    $sql .= (empty($params)) ? " WHERE " : " AND ";
    $sql .= " kategori = :kat";
    $params['kat'] = $_REQUEST["kat"];
}

$sql .= " ORDER BY nama_barang LIMIT 50";
$items = db_fetch_all($bp, $sql, $params);
?>
<header class="page-header">
    <h2><?= htmlspecialchars($header) ?></h2>
    <div class="right-wrapper text-right">
        <ol class="breadcrumbs">
            <li><a href=""><i class="fas fa-calendar"></i></a></li>
        </ol>
        <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fas fa-chevron-left"></i></a>
    </div>
</header>

<!-- start: page -->
<section class="media-gallery">
    <div class="inner-body mg-main">
        <div class="row mg-files" data-sort-destination data-sort-id="media-gallery">
<?php
foreach ($items as $br):
    $link = ($br["jumlah_stok"] > 0) ? "Pilih" : "Pesan";
    $barang = (!empty($br["gambar"]) && file_exists("img/barang/" . $br["gambar"])) ? $br["gambar"] : "barang.png";
?>
            <div class="isotope-item photos col-sm-2 col-md-2 col-lg-2">
                <div class="thumbnail">
                    <div class="thumb-preview">
                        <a class="thumb-image" href="img/barang/<?= htmlspecialchars($barang) ?>">
                            <img src="img/barang/<?= htmlspecialchars($barang) ?>" class="img-fluid" alt="Barang Image">
                        </a>
                        <div class="mg-thumb-options">
                            <div class="mg-zoom"><i class="fas fa-search"></i></div>
                            <div class="mg-toolbar">
                                <div class="mg-group float-right">
                                    <a class="simple-ajax-modal btn btn-dark btn-xs" href="ajax/order.php?id=<?= htmlspecialchars($br['id_barang']) ?>&kunci=<?= urlencode($kunci) ?>"><?= $link ?></a>
                                    <button class="dropdown-toggle mg-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                    <div class="dropdown-menu mg-dropdown" role="menu">
                                        <a class="dropdown-item text-1 simple-ajax-modal" href="ajax/stok_detail.php?id=<?= htmlspecialchars($br['id_barang']) ?>"><i class="fas fa-search"></i> Detail</a>
                                        <a class="dropdown-item text-1 simple-ajax-modal" href="ajax/stok_delete.php?id=<?= htmlspecialchars($br['id_barang']) ?>"><i class="far fa-trash-alt"></i> Hapus</a>
                                        <a class="dropdown-item text-1 simple-ajax-modal" href="ajax/stok_add.php?id=<?= htmlspecialchars($br['id_barang']) ?>"><i class="fas fa-plus"></i> Tambah Stok</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="simple-ajax-modal" href="ajax/order.php?id=<?= htmlspecialchars($br['id_barang']) ?>&kunci=<?= urlencode($kunci) ?>">
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