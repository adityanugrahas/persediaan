<?php
$stmt = $bp->query("SELECT * FROM bagian ORDER BY id_seksi ASC");
$bagian_list = $stmt->fetchAll();
?>

<div class="row">
    <div class="col">
        <section class="card">
            <header class="card-header">
                <div class="card-actions">
                    <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                </div>
                <h2 class="card-title">Data Bidang / Seksi</h2>
            </header>
            <div class="card-body">
                <table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Bidang/Seksi</th>
                            <th>Keterangan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 0;
                        foreach ($bagian_list as $data): 
                            $i++;
                        ?>
                        <tr class='gradeX'>
                            <td><?= $i ?></td>
                            <td><?= htmlspecialchars($data['bagian']) ?></td>
                            <td><?= htmlspecialchars($data['keterangan']) ?></td>
                            <td class="actions">
                                <div class='btn-group'>
                                    <a class='btn btn-sm btn-primary' href='?p=bagian_add&induk=<?= $data['id_seksi'] ?>'>
                                        <i class='fa fa-plus'></i> Sub
                                    </a>
                                    <a class='simple-ajax-modal btn btn-sm btn-success' href='ajax/bagian_edit.php?id=<?= $data['id_seksi'] ?>'>
                                        <i class='fa fa-edit'></i> Edit
                                    </a>
                                    <a class='simple-ajax-modal btn btn-sm btn-danger' href='ajax/delete.php?d=bagian&id=<?= $data['id_seksi'] ?>'>
                                        <i class='fa fa-trash'></i> Hapus
                                    </a>
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
            