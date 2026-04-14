<?php
include("../global/koneksi.php");

$id = $_REQUEST["id"] ?? '';
$d = $_REQUEST["d"] ?? '';

// Generic detail modal — typically used for sub-requests or specific log entries
// Since 'tahap' relates to a legacy "SIKAT" system logic found in the original snippet,
// we'll keep the structure but ensure PDO handles it.
$data = [];
if (!empty($id)) {
    // Attempt to fetch from 'stok_inout' if redirected here for transactional detail
    $data = db_fetch($bp, "SELECT * FROM stok_inout WHERE id_inout = :id", ['id' => $id]);
}

if (!$data) {
    echo "Detail data tidak tersedia.";
    exit;
}
?>
<div id="custom-content" class="modal-block modal-block-md">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class='btn btn-dark btn-sm modal-dismiss'>X</button>
            </div>
            <h2 class="card-title">Detail Transaksi</h2>
        </header>
        <div class="card-body">
            <div class="modal-wrapper">
                <div class="modal-text">
                    <table class="table table-striped table-sm">
                        <tr>
                            <td width="30%">ID Transaksi</td>
                            <td>: <?= htmlspecialchars($data['id_inout']) ?></td>
                        </tr>
                        <tr>
                            <td>Jenis</td>
                            <td>: <?= htmlspecialchars(strtoupper($data['jenis'])) ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>: <?= htmlspecialchars($data['tgl']) ?></td>
                        </tr>
                        <tr>
                            <td>Keterangan</td>
                            <td>: <?= htmlspecialchars($data['keterangan']) ?></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>: <?= ($data['status'] === '2') ? '<span class="badge badge-success">Selesai</span>' : '<span class="badge badge-warning">Pending</span>' ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <hr>
            <div class='text-right'>
                <button type="button" class='btn btn-default modal-dismiss'>Tutup</button>
            </div>
        </div>
    </section>
</div>