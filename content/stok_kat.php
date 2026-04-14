<?php
$k = $_GET["k"] ?? '';
$kat_id = $_REQUEST["kat"] ?? '';
$params = [];
$sql = "SELECT * FROM stok_barang WHERE aktif = 'ya'";

if (!empty($k)) {
    $sql .= " AND (nama_barang ILIKE :search OR keterangan ILIKE :search)";
    $params['search'] = '%' . $k . '%';
}

if (!empty($kat_id)) {
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
                            <th>Nama Barang</th>
                            <th>Keterangan</th>
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
?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td>
                                <figure class='profile-picture'>
                                    <img src='img/barang/<?= htmlspecialchars($barang) ?>' height='50' alt='<?= htmlspecialchars($br['nama_barang']) ?>' class='rounded-circle' />
                                </figure>
                            </td>
                            <td><?= htmlspecialchars($br['nama_barang']) ?></td>
                            <td><?= htmlspecialchars($br['keterangan']) ?></td>
                            <td><?= $br['jumlah_stok'] ?> <?= htmlspecialchars($br['satuan']) ?></td>
                            <td><?= $br['stok_minimal'] ?></td>
                            <td>
                                <div class="input-group mb-3">
                                    <div class="input-group-append">
                                        <a class="simple-ajax-modal btn btn-<?= $warna ?>" href="ajax/<?= $form ?>?id=<?= htmlspecialchars($br['id_barang']) ?>&k=<?= urlencode($k) ?>"><i class="fas fa-shopping-basket"></i> <?= $link ?></a>
                                        <button tabindex="-1" data-toggle="dropdown" class="btn btn-xs btn-warning dropdown-toggle" type="button">
                                            <span class="caret"></span><i class="fa fa-cog"></i>
                                        </button>
                                        <?php if ($_SESSION["levelp"] === "su"): ?>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="?p=stok_edit&id=<?= htmlspecialchars($br['id_barang']) ?>"><i class="fas fa-search"></i> Detail</a>
                                            <a class="dropdown-item simple-ajax-modal" href="ajax/stok_delete.php?id=<?= htmlspecialchars($br['id_barang']) ?>"><i class="far fa-trash-alt"></i> Hapus</a>
                                            <a class="dropdown-item simple-ajax-modal" href="ajax/stok_add.php?id=<?= htmlspecialchars($br['id_barang']) ?>"><i class="fas fa-plus"></i> Tambah Stok</a>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
<?php endforeach; ?>                      
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
<!-- end: page -->
