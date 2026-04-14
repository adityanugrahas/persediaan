<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Cetak Slip</title>
	<meta http-equiv=Content-Type content="text/html; charset=windows-1252">

</head>
<style>
body {
  margin: 1cm;
  padding: 0;
  background-color: #FAFAFA;
  font: 9pt "Tahoma";
}

* {
  box-sizing: border-box;
  -moz-box-sizing: border-box;
}

.page {
  width: 21cm;
  height: 29.7cm;
  padding: 1cm;
  margin: 1cm auto;
  border: 1px #D3D3D3 solid;
  border-radius: 5px;
  background: white;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}

.subpage {
  padding: 1cm;
  border: 5px red solid;
  height: 256mm;
  outline: 2cm #FFEAEA solid;
}

@page {
  size: A4;
  margin: 0;
}

@media print {
  .page {
    margin: 0;
    border: initial;
    border-radius: initial;
    width: initial;
    min-height: initial;
    box-shadow: initial;
    background: initial;
    page-break-after: always;
  }
}	
</style>	
<!--<body>-->
<body onLoad="window.print();">
	<!--<body onLoad="window.print();window.close()">-->
<?php 
include("../global/koneksi.php");
$stmt_set = $bp->query("SELECT * FROM setting LIMIT 1");
$set = $stmt_set->fetch();

$kode = $_REQUEST['kode'] ?? '';

for ($r = 0; $r < 2; $r++) {
    $stmt_slip = $bp->prepare("SELECT * FROM stok_inout WHERE kode_nota = :kode");
    $stmt_slip->execute(['kode' => $kode]);
    $items = $stmt_slip->fetchAll();
    
    if (empty($items)) continue;
    
    $first_item = $items[0];
    
    $stmt_ptg = $bp->prepare("SELECT nama FROM petugas WHERE id_petugas = :id");
    $stmt_ptg->execute(['id' => $first_item['petugas']]);
    $petugas = $stmt_ptg->fetch();
    
    $stmt_sek = $bp->prepare("SELECT bagian FROM bagian WHERE id_seksi = :id");
    $stmt_sek->execute(['id' => $first_item['id_seksi']]);
    $subseksi = $stmt_sek->fetch();
?>
    <table border="1px 1px 1px 1px" bordercolor="#ccc" cellpadding="10" cellspacing="0" width="100%" height="50%">
        <tr>
            <td align="center" colspan="2">
                <table border="0px" width="100%">
                    <tr>
                        <td width="10%"><img src="../img/<?= htmlspecialchars($set['logo_header'] ?? '') ?>" height="30px"></td>
                        <td align="center">
                            <?= htmlspecialchars($set['nama_kantor'] ?? '') ?><br>
                            <?= htmlspecialchars($set['alamat_kantor'] ?? '') ?> - <?= htmlspecialchars($set['telp_kantor'] ?? '') ?>
                        </td>
                    </tr>
                </table><hr>
                SLIP PERMINTAAN PEMAKAIAN BARANG PERSEDIAAN
            </td>
        </tr>
        <tr><td width="80"> Kode</td><td>: <?= htmlspecialchars($first_item['kode_nota']) ?></td></tr>
        <tr><td width="80"> Tanggal</td><td>: <?= htmlspecialchars($first_item['tgl']) ?></td></tr>
        <tr><td width="80"> Pemohon</td><td>: <?= htmlspecialchars(($petugas['nama'] ?? '') . " - " . ($subseksi['bagian'] ?? '')) ?></td></tr>
        <tr>
            <td colspan="2"><br>
                <table border="1" width="100%" style="border: thick #000000 1px" cellpadding="5" cellspacing="0">
                    <tr align="center" style="font-weight: bolder">
                        <td>No</td>
                        <td>Nama Barang</td>
                        <td>Jumlah</td>
                        <td>Keterangan</td>
                        <td>Catatan Admin</td>
                    </tr>
                    <?php 
                    $i = 0; 
                    foreach ($items as $req):
                        $i++;
                        $stmt_brg = $bp->prepare("SELECT nama_barang, satuan FROM stok_barang WHERE id_barang = :id");
                        $stmt_brg->execute(['id' => $req['id_barang']]);
                        $brg = $stmt_brg->fetch();
                        
                        $jumlah = !empty($req["jml_out"]) ? $req["jml_out"] : $req["jml_req"];
                    ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= htmlspecialchars($brg['nama_barang'] ?? 'Unknown Item') ?></td>
                            <td align="center"><?= htmlspecialchars($jumlah . " " . ($brg['satuan'] ?? '')) ?></td>
                            <td><?= htmlspecialchars($req['keterangan'] ?? '') ?></td>
                            <td><?= htmlspecialchars($req['catatan'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table><br>
                <table width="100%" border="0">
                    <tr>
                        <td width="40%" align="center">Menyetujui</td>
                        <td width="30%" align="center">Mengetahui</td>
                        <td align="center">Pemohon</td>
                    </tr>
                    <tr height="80" valign="bottom" align="center">
                        <td>( ................................... )</td>
                        <td>( ................................... )</td>
                        <td>( <?= htmlspecialchars($petugas['nama'] ?? '') ?> )</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <hr style="border: dashed thin">
<?php } ?>
	
</body>
</html>
