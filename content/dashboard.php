<?php
// Fetch stats for dashboard
$total_items = $bp->query("SELECT COUNT(*) FROM stok_barang")->fetchColumn();
$total_sections = $bp->query("SELECT COUNT(*) FROM bagian")->fetchColumn();
$total_pending = $bp->query("SELECT COUNT(*) FROM stok_inout WHERE status = '1'")->fetchColumn();
$total_users = $bp->query("SELECT COUNT(*) FROM petugas")->fetchColumn();

// Fetch latest requests
$stmt_recent = $bp->query("SELECT i.*, b.nama_barang, p.nama as pemohon 
                          FROM stok_inout i 
                          JOIN stok_barang b ON i.id_barang = b.id_barang 
                          JOIN petugas p ON i.petugas = p.id_petugas 
                          ORDER BY i.tgl DESC LIMIT 5");
$recent_requests = $stmt_recent->fetchAll();
?>

<div class="row">
    <!-- Stats Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <section class="card card-featured-left card-featured-primary">
            <div class="card-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-primary"><i class="fas fa-boxes"></i></div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">Total Barang</h4>
                            <div class="info"><strong class="amount"><?= $total_items ?></strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <section class="card card-featured-left card-featured-secondary">
            <div class="card-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-secondary"><i class="fas fa-sitemap"></i></div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">Total Bidang</h4>
                            <div class="info"><strong class="amount"><?= $total_sections ?></strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <section class="card card-featured-left card-featured-tertiary">
            <div class="card-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-tertiary"><i class="fas fa-clock"></i></div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">Pending Request</h4>
                            <div class="info"><strong class="amount"><?= $total_pending ?></strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <section class="card card-featured-left card-featured-quaternary">
            <div class="card-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-quaternary"><i class="fas fa-users"></i></div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">Total Petugas</h4>
                            <div class="info"><strong class="amount"><?= $total_users ?></strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <section class="card">
            <header class="card-header">
                <h2 class="card-title">Permintaan Terbaru</h2>
            </header>
            <div class="card-body">
                <table class="table table-responsive-md table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Petugas</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_requests as $req): ?>
                        <tr>
                            <td><?= date("d/m/Y H:i", strtotime($req['tgl'])) ?></td>
                            <td><?= htmlspecialchars($req['pemohon']) ?></td>
                            <td><?= htmlspecialchars($req['nama_barang']) ?></td>
                            <td><?= $req['jml_req'] ?></td>
                            <td>
                                <?php if($req['status'] == '1'): ?>
                                    <span class="badge badge-warning">Pending</span>
                                <?php elseif($req['status'] == '2'): ?>
                                    <span class="badge badge-success">Selesai</span>
                                <?php else: ?>
                                    <span class="badge badge-info">Draf</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
    <div class="col-lg-4">
        <section class="card">
            <header class="card-header">
                <h2 class="card-title">Stok Menipis</h2>
            </header>
            <div class="card-body">
                <ul class="simple-user-list">
                    <?php
                    $stmt_low = $bp->query("SELECT nama_barang, jumlah_stok, stok_minimal 
                                           FROM stok_barang 
                                           WHERE jumlah_stok <= stok_minimal 
                                           LIMIT 5");
                    while($low = $stmt_low->fetch()) {
                        echo "<li>
                                <span class='title'>".htmlspecialchars($low['nama_barang'])."</span>
                                <span class='message'>Sisa: <b>{$low['jumlah_stok']}</b> (Min: {$low['stok_minimal']})</span>
                              </li>";
                    }
                    ?>
                </ul>
            </div>
        </section>
    </div>
</div>
