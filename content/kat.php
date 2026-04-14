<!-- start: page -->
<div class="row">
    <div class="col">
        <section class="card">
            <header class="card-header">
                <div class="card-actions">
                    <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                </div>
                <h2 class="card-title">Data Kategori Barang</h2>
            </header>
            <div class="card-body">
                <table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Kategori</th>
                            <th>Keterangan</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
$i = 1;
$items = db_fetch_all($bp, "SELECT * FROM kategori ORDER BY id_kat ASC");
foreach ($items as $data):
?>
                        <tr class='gradeX'>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($data['nama_kat']) ?></td>
                            <td width='50%'><?= htmlspecialchars($data['ket_kat']) ?></td>
                            <td>
                                <div class='btn-group' role='group'>
                                    <a class='simple-ajax-modal modal-with-form btn btn-xs btn-success' href='ajax/kat_edit.php?id=<?= htmlspecialchars($data['id_kat']) ?>' title='Edit'><i class='fa fa-edit'></i> </a>
                                    <a class='simple-ajax-modal btn btn-xs btn-danger' href='ajax/delete.php?d=kat&id=<?= htmlspecialchars($data['id_kat']) ?>' title='Hapus'><i class='fa fa-trash'></i> </a>
                                    <a class='btn btn-xs btn-primary' href='?p=stok&kat=<?= htmlspecialchars($data['id_kat']) ?>' title='Lihat Stok'><i class='fa fa-list'></i> </a>
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