<!-- start: page -->
<?php
if ($_SESSION["statep"] == "login" && ($_SESSION["levelp"] == "su" || $_SESSION["levelp"] == "adm")):
    $bag_nm = htmlspecialchars($_REQUEST["bag_nm"] ?? '');
?>
<div class="row">
    <div class="col">
        <section class="card">
            <header class="card-header">
                <div class="card-actions">
                    <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                </div>
                <h2 class="card-title">Data Penanggung Jawab <?= $bag_nm ?></h2>
            </header>
            <div class="card-body">
                <table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th width="70">Photo</th>
                            <th>Nama</th>
                            <th>Seksi / Bagian</th>
                            <th width="100">Jumlah Aset</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
$i = 1;
$holders = db_fetch_all($bp, "SELECT DISTINCT d.pengguna, p.nama, p.photo, b.bagian, p.id_petugas 
                              FROM bmn_dist d 
                              JOIN petugas p ON d.pengguna = p.id_petugas 
                              LEFT JOIN bagian b ON p.seksi = b.id_seksi 
                              ORDER BY p.nama ASC");

foreach ($holders as $data):
    $usage = db_fetch($bp, "SELECT SUM(jumlah_pakai) as j_pakai, SUM(jumlah_kembali) as j_kembali FROM bmn_dist WHERE pengguna = :id", ['id' => $data['pengguna']]);
    $jaset = ($usage['j_pakai'] ?? 0) - ($usage['j_kembali'] ?? 0);
    $photo = (!empty($data["photo"])) ? $data["photo"] : "user.jpg";
?>
                        <tr class="gradeX">
                            <td><?= $i++ ?></td>
                            <td>
                                <figure class='profile-picture'>
                                    <img src='img/users/<?= htmlspecialchars($photo) ?>' height='50' alt='Photo' class='rounded-circle' />
                                </figure>
                            </td>
                            <td><?= htmlspecialchars($data['nama']) ?></td>
                            <td><?= htmlspecialchars($data['bagian'] ?? '-') ?></td>
                            <td><?= $jaset ?></td>
                            <td>
                                <a class='btn btn-xs btn-success' href='?p=bmn_pemegang_det&id=<?= htmlspecialchars($data['id_petugas']) ?>'><i class='fa fa-search'></i> Detail</a>
                            </td>
                        </tr>
<?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
<?php else: ?>
<script>
    window.location = "index.php";
</script>
<?php endif; ?>
<!-- end: page -->