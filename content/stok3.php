<?php
$kunci = $_POST["kunci"] ?? '';
$kat_id = $_REQUEST["kat"] ?? '';
$params = [];
$sql = "SELECT * FROM stok_barang";

if (!empty($kunci)) {
    $sql .= " WHERE (nama_barang ILIKE :kunci OR keterangan ILIKE :kunci)";
    $params['kunci'] = '%' . $kunci . '%';
}

if (!empty($kat_id)) {
    $sql .= (empty($params)) ? " WHERE " : " AND ";
    $sql .= " kategori = :kat";
    $params['kat'] = $kat_id;
}

$sql .= " ORDER BY nama_barang LIMIT 50";
$items = db_fetch_all($bp, $sql, $params);
?>
<header class="page-header">
    <h2>Stok Barang</h2>
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
    $barang = (!empty($br["gambar"]) && file_exists("img/barang/" . $br["gambar"])) ? $br["gambar"] : "barang.png";
?>
            <div class="col-sm-3 col-md-3 col-lg-2">
                <section class="card mb-4 shadow-sm">
                    <div class="card-body p-2 text-center">
                        <div class="thumb-info mb-2">
                            <img src="img/barang/<?= htmlspecialchars($barang) ?>" class="img-fluid rounded" style="height: 120px; object-fit: cover;" alt="Barang Image">
                            <div class="thumb-info-title">
                                <span class="thumb-info-inner" style="font-size: 0.8rem;"><?= htmlspecialchars($br['nama_barang']) ?></span>
                            </div>
                        </div>
                        <div class="widget-toggle-expand mb-1">
                            <div class="widget-header">
                                <h6 class="mb-1">Stok: <?= $br['jumlah_stok'] ?> <?= htmlspecialchars($br['satuan']) ?></h6>
                            </div>
                        </div>
                        <a href="ajax/order.php?id=<?= htmlspecialchars($br['id_barang']) ?>" class="simple-ajax-modal btn btn-primary btn-xs btn-block">Pesan</a>
                    </div>
                </section>
            </div>
<?php endforeach; ?>
        </div>
    </div>
</section>
<!-- end: page -->