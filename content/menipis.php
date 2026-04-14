<!-- start: page -->

<div class="row">
<div class="col">
<section class="card">
    <header class="card-header">
        <div class="card-actions">
            <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
        </div>
<?php
$i = 0;
$items = db_fetch_all($bp, "SELECT * FROM stok_barang WHERE jumlah_stok <= stok_minimal ORDER BY id_barang ASC");
$jum = count($items);
?>
        <h2 class="card-title">Barang Hampir Habis (<?= $jum ?> item barang)</h2>
    </header>
    <div class="card-body">
        <table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
        <thead>
        <tr>
            <th>Photo</th>
            <th>Nama Barang</th>
            <th>Keterangan</th>
            <th>Stok Minimal</th>
            <th>Jumlah Stok</th>
            <th>Action</th>
        </tr>
    </thead>
            <tbody>
<?php foreach ($items as $data):
    $i++;
    $photo = (!empty($data["gambar"])) ? $data["gambar"] : "not_exist.PNG";
?>
    <tr class="gradeX">
        <td><figure class='profile-picture'>
            <img src='img/barang/<?= htmlspecialchars($photo) ?>' height='50' alt='<?= htmlspecialchars($data['nama_barang']) ?>' class='rounded-circle' />
        </figure></td>
        <td><?= htmlspecialchars($data['nama_barang']) ?></td>
        <td><?= htmlspecialchars($data['keterangan']) ?></td>
        <td><?= $data['stok_minimal'] ?></td>
        <td><?= $data['jumlah_stok'] ?></td>
        <td width='auto'>
            <div class='btn-group flex-wrap'>
            <a class='simple-ajax-modal btn btn-xs btn-primary' href='ajax/stok_add.php?d=brg&id=<?= htmlspecialchars($data['id_barang']) ?>'><i class='fa fa-plus'></i></a>
            <a class='btn btn-xs btn-success' href='?p=stok_edit&id=<?= htmlspecialchars($data['id_barang']) ?>'><i class='fa fa-edit'></i></a>
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