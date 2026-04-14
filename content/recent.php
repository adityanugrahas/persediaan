<!-- start: page -->
<div class="row">
    <div class="col">
        <section class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
                    <thead>
                        <tr>
                            <th width="30">No</th>
                            <th width="70">Photo</th>
                            <th>Nama Barang</th>
                            <th>Jumlah Stok</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
$i = 1;
$items = db_fetch_all($bp, "SELECT DISTINCT(i.id_barang), b.jumlah_stok, b.nama_barang, b.keterangan, b.satuan, b.gambar 
                             FROM stok_inout i 
                             JOIN stok_barang b ON i.id_barang = b.id_barang 
                             WHERE i.id_seksi = :seksi 
                             ORDER BY i.tgl DESC", ['seksi' => $_SESSION['seksip']]);

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
                                    <img src='img/barang/<?= htmlspecialchars($barang) ?>' height='50' alt='Photo' class='rounded-circle' />
                                </figure>
                            </td>
                            <td>
                                <?= htmlspecialchars($br['nama_barang']) ?><br>
                                <span class='text-primary small'><?= htmlspecialchars($br['keterangan']) ?></span>
                            </td>
                            <td><?= $br['jumlah_stok'] ?> <?= htmlspecialchars($br['satuan']) ?></td>
                            <td>
                                <div class="input-group mb-0">
                                    <div class="input-group-append">
                                        <a class="simple-ajax-modal btn btn-<?= $warna ?> btn-xs" href="ajax/<?= $form ?>?id=<?= htmlspecialchars($br['id_barang']) ?>"><i class="fas fa-shopping-basket"></i> <?= $link ?></a>
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
