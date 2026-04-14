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
$f_label = "";
$f = htmlspecialchars($_REQUEST["f"] ?? '');
if ($f === "pakai") { $f_label = "Pemakaian"; }
elseif ($f === "masuk") { $f_label = "Penambahan"; }

$periode = htmlspecialchars($_GET["periode"] ?? date("Y-m"));

$bag = htmlspecialchars($_GET["bag"] ?? '');
$bagian = "";
$bagian2 = "Semua Seksi";
$params = [];

if (!empty($bag)) {
    $bg = db_fetch($bp, "SELECT bagian FROM bagian WHERE id_seksi = :id", ['id' => $bag]);
    $bagian = htmlspecialchars($bg['bagian'] ?? '');
    $bagian2 = $bagian;
}
?>
<h2 class="card-title">Daftar Permintaan Barang <?= $bagian ?></h2>
    </header>
    <div class="card-body">
    <div class="alert alert-info nomargin">
            <form action="" method="get" class="logo">
            <input type="hidden" name="p" value="permintaan">
            <input type="hidden" name="f" value="<?= $f ?>">
            <label class="col-lg-2 control-label pt-2">Periode</label>
                <input type="month" name='periode' value="<?= $periode ?>">
            <?php if ($_SESSION["levelp"] === "su"): ?>
                <select name="bag" class="btn btn-sm btn-default">
                    <option value='<?= $bag ?>'><?= $bagian2 ?></option>
                <?php
                $bagians = db_fetch_all($bp, "SELECT id_seksi, bagian FROM bagian");
                foreach ($bagians as $b) {
                    echo "<option value='" . htmlspecialchars($b['id_seksi']) . "'>" . htmlspecialchars($b['bagian']) . "</option>";
                }
                ?>
                    <option value=''>Semua</option>
                </select>
            <?php endif; ?>
             <input type='submit' class="btn btn-sm btn-primary" value='Tampilkan'>
            </form>
        </div>
    <?php
// Build query with PDO params
$sql = "SELECT DISTINCT kode_nota, petugas, id_seksi, status FROM stok_inout WHERE status != '0' AND jenis = 'out'";
$params = [];

if ($_SESSION["levelp"] === "opr") {
    $sql .= " AND petugas = :ptg";
    $params['ptg'] = $_SESSION['idp'];
}

if (!empty($_REQUEST["kode"])) {
    $sql .= " AND kode_nota = :kode";
    $params['kode'] = $_REQUEST["kode"];
}

if (!empty($bag)) {
    $sql .= " AND id_seksi = :bag";
    $params['bag'] = $bag;
}

$sql .= " AND tgl::text LIKE :periode ORDER BY status, tgl DESC";
$params['periode'] = $periode . '%';

$rows = db_fetch_all($bp, $sql, $params);
$jum = count($rows);

if ($jum > 0):
?>
        
        <table class="table table-bordered table-hover table-responsive-md table-sm mb-0" id="datatable-tabletools">
        <thead>
        <tr>
            <th>No</th>
            <th>Kode Nota</th>
            <th>Detail Barang</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
            <tbody>
<?php foreach ($rows as $data):
    $i++;
    $ptg = db_fetch($bp, "SELECT nama FROM petugas WHERE id_petugas = :id", ['id' => $data['petugas']]);
    $sek = db_fetch($bp, "SELECT bagian FROM bagian WHERE id_seksi = :id", ['id' => $data['id_seksi']]);
    
    if ($data["status"] == '1') {
        $status = "<span class='text-danger'>Belum Disetujui</span>";
        $pros = "<a href='?p=prosesacc&kode=" . htmlspecialchars($data['kode_nota']) . "' class='btn btn-xs btn-success'><i class='fa fa-check'></i></a>";
    } elseif ($data["status"] == '2') {
        $status = "<span class='text-success'>Sudah Disetujui</span>";
        $pros = "";
    }
?>
    <tr class="gradeX">
        <td><?= $i ?></td>
        <td><b><?= htmlspecialchars($ptg['nama'] ?? '') ?> - <?= htmlspecialchars($sek['bagian'] ?? '') ?></b><br>
            <?= htmlspecialchars($data['kode_nota']) ?></td>
        <td>
            <table width='100%' class='table table-responsive-md table-sm mb-0' style='border:0px solid #000;'>
            <tbody>
<?php
    $items = db_fetch_all($bp, "SELECT i.id_barang, i.jml_req, i.jml_out, i.satuan, i.status, i.keterangan, i.catatan, b.nama_barang 
                                FROM stok_inout i JOIN stok_barang b ON i.id_barang = b.id_barang 
                                WHERE i.kode_nota = :kode", ['kode' => $data['kode_nota']]);
    foreach ($items as $brg):
        $jumlah = ($brg['status'] == '1') ? $brg['jml_req'] : $brg['jml_out'];
?>
                <tr>
                    <td><?= htmlspecialchars($brg['nama_barang']) ?></td>
                    <td width='10%'><?= $jumlah ?></td>
                    <td width='10%'><?= htmlspecialchars($brg['satuan']) ?></td>
                    <td width='20%'>ket : <?= htmlspecialchars($brg['keterangan']) ?></td>
                    <td width='20%'>Catatan : <?= htmlspecialchars($brg['catatan']) ?></td>
                </tr>
<?php endforeach; ?>
            </tbody></table></td>
        <td><?= $status ?></td>
        <td>
            <a href='ajax/permintaan_det.php?kode=<?= htmlspecialchars($data['kode_nota']) ?>' class='simple-ajax-modal btn btn-xs btn-warning'><i class='fa fa-search'></i></a>
            <a onclick="popUp('cetak/cetakslip.php?kode=<?= htmlspecialchars($data['kode_nota']) ?>')" class="btn btn-xs btn-default ml-1" style="cursor:pointer;"><i class="fa fa-print"></i></a>
            <?php if ($_SESSION["levelp"] === "su") { echo $pros; } ?>
        </td>
    </tr>
<?php endforeach; ?>
                        </tbody>
                    </table>
<?php else: ?>
    <div class="alert alert-warning mt-3"><i class="fas fa-inbox mr-1"></i> Data tidak ditemukan.</div>
<?php endif; ?>
                </div>
            </section>
        </div>
    </div>
                     
<!-- end: page -->
