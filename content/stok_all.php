<?php
/**
 * Inventory Management: Full List View
 * Premium Proportional Table Design
 */
$k = htmlspecialchars($_GET["k"] ?? '');
$header = "Stok Barang";
$params = [];
$sql = "SELECT * FROM stok_barang WHERE aktif = 'ya'";

if (!empty($k)) {
    $header = $k;
    $sql .= " AND (nama_barang LIKE :search OR keterangan LIKE :search)";
    $params['search'] = '%' . $k . '%';
}

if (!empty($_REQUEST["kat"])) {
    $kat_id = htmlspecialchars($_REQUEST["kat"]);
    $sql .= " AND kategori = :kat";
    $params['kat'] = $kat_id;
}

$sql .= " ORDER BY nama_barang";
$items = db_fetch_all($bp, $sql, $params);
?>

<div class="row animate__animated animate__fadeIn">
    <div class="col">
        <section class="card glass-card">
            <header class="card-header border-bottom-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="card-title text-white font-weight-bold"><?= htmlspecialchars($header) ?></h2>
                        <p class="text-muted small mb-0">Total: <?= count($items) ?> item terdaftar</p>
                    </div>
                </div>
            </header>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-modern" id="datatable-tabletools">
                        <thead>
                            <tr>
                                <th class="text-center" width="50">NO</th>
                                <th width="80">COVER</th>
                                <th width="300">NAMA & KETERANGAN</th>
                                <th width="150">KATEGORI</th>
                                <th class="text-center" width="150">STOK SAAT INI</th>
                                <th class="text-center" width="150">STOK AMAN</th>
                                <th class="text-right" width="200">OPSI CEPAT</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
$i = 1;
foreach ($items as $br):
    $link = ($br["jumlah_stok"] > 0) ? "PILIH" : "PESAN";
    $warna = ($br["jumlah_stok"] > 0) ? "primary" : "danger";
    $form = ($br["jumlah_stok"] > 0) ? "order.php" : "pesan.php";
    $barang = (!empty($br["gambar"]) && file_exists("img/barang/" . $br["gambar"])) ? $br["gambar"] : "barang.png";
    $kat_row = db_fetch($bp, "SELECT nama_kat FROM kategori WHERE id_kat = :id", ['id' => $br['kategori']]);
    $kategori_name = !empty($kat_row['nama_kat']) ? $kat_row['nama_kat'] : "—";
    $stok_class = ($br["jumlah_stok"] <= $br["stok_minimal"]) ? "text-danger-glow font-weight-bold" : "text-primary-glow";
?>
                            <tr class="align-middle">
                                <td class="text-center text-muted small"><?= $i ?></td>
                                <td>
                                    <div class="img-container">
                                        <img src='img/barang/<?= htmlspecialchars($barang) ?>' height='45' width='45' class='rounded-circle shadow-sm border-glass' />
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-white font-weight-bold mb-0"><?= htmlspecialchars($br['nama_barang']) ?></h6>
                                    <p class="text-muted xs-text mb-0"><?= htmlspecialchars($br['keterangan'] ?: 'Tidak ada deskripsi') ?></p>
                                </td>
                                <td>
                                    <span class="badge badge-glass"><?= htmlspecialchars($kategori_name) ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="<?= $stok_class ?> h5 mb-0"><?= $br['jumlah_stok'] ?></span>
                                    <small class="text-muted d-block"><?= htmlspecialchars($br['satuan']) ?></small>
                                </td>
                                <td class="text-center">
                                    <span class="text-white opacity-50"><?= $br['stok_minimal'] ?></span>
                                </td>
                                <td class="text-right">
                                    <div class="btn-group">
                                        <a href="ajax/<?= $form ?>?id=<?= htmlspecialchars($br['id_barang']) ?>" class="btn btn-<?= $warna ?> btn-sm px-4 simple-ajax-modal" style="border-radius: 20px 0 0 20px;">
                                            <?= $link ?>
                                        </a>
                                        <?php if ($_SESSION["levelp"] === "su"): ?>
                                        <div class="btn-group btn-group-sm ml-0">
                                            <button type="button" class="btn btn-<?= $warna ?> btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" style="border-radius: 0 20px 20px 0;">
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="?p=stok_edit&id=<?= htmlspecialchars($br['id_barang']) ?>"><i class="fas fa-edit mr-2"></i> Edit Data</a>
                                                <a class="dropdown-item simple-ajax-modal" href="ajax/stok_add.php?id=<?= htmlspecialchars($br['id_barang']) ?>"><i class="fas fa-plus-circle mr-2"></i> Tambah Stok</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item simple-ajax-modal text-danger" href="ajax/delete.php?d=stok&id=<?= htmlspecialchars($br['id_barang']) ?>&nm=<?= urlencode($br['nama_barang']) ?>"><i class="fas fa-trash mr-2"></i> Hapus</a>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
<?php $i++; endforeach; ?>                      
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

<style>
.table-modern {
    border-collapse: separate !important;
    border-spacing: 0 10px !important;
    background: transparent !important;
}
.table-modern producing_thead th {
    background: rgba(255,255,255,0.03) !important;
    border: none !important;
    text-transform: uppercase;
    font-size: 0.65rem;
    letter-spacing: 0.1em;
    padding: 15px 20px !important;
    color: var(--text-muted);
}
.table-modern tbody tr {
    background: var(--glass-soft) !important;
    backdrop-filter: blur(5px);
    transition: var(--transition-smooth);
}
.table-modern tbody tr:hover {
    background: var(--glass-medium) !important;
    transform: scale(1.002);
}
.table-modern tbody td {
    border: none !important;
    padding: 15px 20px !important;
    vertical-align: middle !important;
}
.table-modern tbody tr td:first-child { border-radius: 12px 0 0 12px; }
.table-modern tbody tr td:last-child { border-radius: 0 12px 12px 0; }

.badge-glass {
    background: rgba(255,255,255,0.05);
    border: 1px solid var(--glass-border);
    color: #fff;
    font-weight: 500;
    padding: 5px 12px;
}
.text-danger-glow { color: #ef4444; text-shadow: 0 0 10px rgba(239,68,68,0.3); }
.text-primary-glow { color: var(--primary); text-shadow: 0 0 10px var(--primary-glow); }
.border-glass { border: 2px solid var(--glass-border) !important; }
</style>
