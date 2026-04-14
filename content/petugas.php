<?php
$query_str = "SELECT p.*, b.bagian 
              FROM petugas p 
              JOIN bagian b ON p.seksi = b.id_seksi";
$params = [];

if (!empty($_REQUEST["id_seksi"])) {
    $query_str .= " WHERE p.seksi = :seksi";
    $params['seksi'] = $_REQUEST["id_seksi"];
}

$query_str .= " ORDER BY p.id_petugas ASC";
$stmt = $bp->prepare($query_str);
$stmt->execute($params);
$petugas_list = $stmt->fetchAll();
?>

<div class="row">
    <div class="col">
        <section class="card">
            <header class="card-header">
                <div class="card-actions">
                    <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                </div>
                <h2 class="card-title">Data Petugas</h2>
            </header>
            <div class="card-body">
                <table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Photo</th>
                            <th>Nama</th>
                            <th>User ID</th>
                            <th>Seksi / Bagian</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 0;
                        foreach ($petugas_list as $data): 
                            $i++;
                            $photo = !empty($data["photo"]) ? $data["photo"] : "user.jpg";
                        ?>
                        <tr class='gradeX'>
                            <td><?= $i ?></td>
                            <td>
                                <figure class='profile-picture'>
                                    <img src='img/users/<?= htmlspecialchars($photo) ?>' height='40' class='rounded-circle' />
                                </figure>
                            </td>
                            <td><?= htmlspecialchars($data['nama']) ?></td>
                            <td><?= htmlspecialchars($data['users_id']) ?></td>
                            <td><?= htmlspecialchars($data['bagian']) ?></td>
                            <td><?= htmlspecialchars($data['p_status']) ?></td>
                            <td class="actions">
                                <div class='btn-group'>
                                    <a class='simple-ajax-modal btn btn-sm btn-primary' href='ajax/editpeg.php?d=petugas&id=<?= $data['id_petugas'] ?>'>
                                        <i class='fa fa-edit'></i> Edit
                                    </a>
                                    <a class='simple-ajax-modal btn btn-sm btn-danger' href='ajax/delete.php?d=petugas&id=<?= $data['id_petugas'] ?>'>
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
            