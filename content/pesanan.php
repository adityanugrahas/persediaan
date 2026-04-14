<!-- start: page -->
<script>
function popUp(URL) {
    window.open(URL, '_blank', 'toolbar=0,scrollbars=1,location=0,width=800,height=1000,statusbar=0,menubar=0,resizable=1,left=300,top=100');
}
</script>
<div class="row">
<div class="col">
<section class="card">
    <header class="card-header">
        <div class="card-actions">
            <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
        </div>
<?php
$i = 0;
$rows = db_fetch_all($bp, "SELECT p.*, t.nama FROM pesanan p JOIN petugas t ON p.petugas = t.id_petugas ORDER BY p.jumlah_stok ASC");
$jum = count($rows);
?>
        <h2 class="card-title">Daftar Pesanan Barang</h2>
    </header>
    <div class="card-body">
        <table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
        <thead>
        <tr>
            <th>No</th>
            <th>Pemesan</th>
            <th>Nama Barang</th>
            <th>Deskripsi Brg</th>
            <th>Jumlah Pesanan</th>
            <th>Jumlah Stok</th>
            <th>Keterangan</th>
            <th>Action</th>
        </tr>
    </thead>
            <tbody>
<?php foreach ($rows as $data):
    $i++;
    $brg = db_fetch($bp, "SELECT nama_barang, jumlah_stok, satuan FROM stok_barang WHERE id_barang = :id", ['id' => $data['id_barang']]);
?>
    <tr class="gradeX">
        <td><?= $i ?></td>
        <td><?= htmlspecialchars($data['nama']) ?></td>
        <td><?= htmlspecialchars($data['nama_barang']) ?></td>
        <td><?= htmlspecialchars($data['ket_barang']) ?></td>
        <td><?= $data['jumlah'] ?> <?= htmlspecialchars($data['satuan']) ?></td>
        <td><?= $brg['jumlah_stok'] ?? 0 ?> <?= htmlspecialchars($brg['satuan'] ?? '') ?></td>
        <td><?= htmlspecialchars($data['keterangan']) ?></td>
        <td width='auto' class='text-right'>
        <?php if (!empty($data["lampiran"])): ?>
            <a onclick="popUp('lampiran/<?= htmlspecialchars($data['lampiran']) ?>')" class="btn btn-xs btn-default ml-1" style="cursor:pointer;"><i class="fa fa-file"></i></a>
        <?php endif; ?>
        <?php
        if ($_SESSION["levelp"] === "su") {
            if ($data["id_barang"] === "baru") {
                $form = "<a class='btn btn-xs btn-warning' href='?p=stok_add&ps=" . htmlspecialchars($data['id_pesanan']) . "'><i class='fa fa-plus'></i></a>";
            } else {
                $form = "<a class='simple-ajax-modal btn btn-xs btn-primary' href='ajax/stok_add.php?d=brg&id=" . htmlspecialchars($data['id_barang']) . "'><i class='fa fa-plus'></i></a>";
            }
        } else {
            if (($brg['jumlah_stok'] ?? 0) > 0) {
                $form = "<a class='simple-ajax-modal btn btn-xs btn-primary' href='ajax/order.php?id=" . htmlspecialchars($data['id_barang']) . "'><i class='fa fa-shopping-cart'></i></a>";
            } else {
                $form = "";
            }
        }
        echo $form;
        ?>
            <a class='simple-ajax-modal btn btn-xs btn-danger' href='ajax/delete.php?d=pesanan&id=<?= htmlspecialchars($data['id_pesanan']) ?>'><i class='fa fa-trash'></i></a>
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