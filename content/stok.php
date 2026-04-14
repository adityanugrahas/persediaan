<?php
$query = "SELECT * FROM stok_barang WHERE aktif = 'ya'";
$params = [];

if (!empty($_GET["k"])) {
    $k = $_GET["k"];
    $header = htmlspecialchars($k);
    $query .= " AND (nama_barang LIKE :k OR keterangan LIKE :k)";
    $params['k'] = "%$k%";
} else {
    $header = "Stok Barang";
    $k = "";
}

if (!empty($_REQUEST["kat"])) {
    $kat = $_REQUEST["kat"];
    $header = htmlspecialchars($kat);
    $query .= " AND kategori = :kat";
    $params['kat'] = $kat;
}

$query .= " ORDER BY nama_barang LIMIT 50";

$stmt = $bp->prepare($query);
$stmt->execute($params);
?>

<section class="media-gallery">
    <div class="inner-body mg-main">
        <header class="mb-4">
            <h2 class="font-weight-bold text-dark"><?= $header ?></h2>
        </header>
        <div class="row mg-files">
            <?php
            while($br = $stmt->fetch()):
                $status_link = ($br["jumlah_stok"] > 0) ? ["Pilih", "primary", "order.php"] : ["Pesan", "danger", "pesan.php"];
                $image = (!empty($br["gambar"]) && file_exists("img/barang/" . $br["gambar"])) ? $br["gambar"] : "barang.png";
            ?>
            <div class="isotope-item photos col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-4">
                <div class="thumbnail">
                    <div class="thumb-preview">
                        <a class="thumb-image" href="img/barang/<?= $image ?>">
                            <img src="img/barang/<?= $image ?>" class="img-fluid" alt="<?= htmlspecialchars($br['nama_barang']) ?>" style="height: 150px; object-fit: cover; width: 100%;">
                        </a>
                        <div class="mg-thumb-options">
                            <div class="mg-zoom"><i class="fas fa-search"></i></div>
                            <div class="mg-toolbar">
                                <div class="mg-group float-right">
                                    <a class="simple-ajax-modal btn btn-<?= $status_link[1] ?> btn-xs" href="ajax/<?= $status_link[2] ?>?id=<?= $br['id_barang'] ?>&k=<?= urlencode($k) ?>">
                                        <?= $status_link[0] ?>
                                    </a>
                                    <?php if($_SESSION["levelp"] == "su"): ?>
                                    <button class="dropdown-toggle mg-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                    <div class="dropdown-menu mg-dropdown" role="menu">
                                        <a class="dropdown-item" href="?p=stok_edit&id=<?= $br['id_barang'] ?>"><i class="fas fa-edit"></i> Edit Detail</a>
                                        <a class="dropdown-item simple-ajax-modal" href="ajax/stok_delete.php?id=<?= $br['id_barang'] ?>"><i class="far fa-trash-alt"></i> Hapus</a>
                                        <a class="dropdown-item simple-ajax-modal" href="ajax/stok_add.php?id=<?= $br['id_barang'] ?>"><i class="fas fa-plus"></i> Tambah Stok</a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-2">
                        <a class="simple-ajax-modal text-dark" href="ajax/<?= $status_link[2] ?>?id=<?= $br['id_barang'] ?>&k=<?= urlencode($k) ?>">
                            <h5 class="mg-title font-weight-bold mb-1 text-2"><?= htmlspecialchars($br['nama_barang']) ?></h5>
                        </a>
                        <div class="mg-description d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="fas fa-layer-group"></i> <?= $br['jumlah_stok'] ?> <?= $br['satuan'] ?></small>
                            <small class="text-muted text-truncate ml-2" title="<?= htmlspecialchars($br['keterangan']) ?>"><?= htmlspecialchars($br['keterangan']) ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
