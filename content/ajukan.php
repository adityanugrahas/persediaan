<script>
function popUp(URL) {
    window.open(URL, '_blank', 'toolbar=0,scrollbars=1,location=0,width=800,height=1000,statusbar=0,menubar=0,resizable=1,left=300,top=100');
}
</script>
<?php
$tanggal = date("ymd.Hi-s");
$sek = db_fetch($bp, "SELECT bagian FROM bagian WHERE id_seksi = :seksi", ['seksi' => $_SESSION['seksip']]);
$i = 1;

// Update pending items to status 1 with kode_nota
$bp->prepare("UPDATE stok_inout SET status = '1', kode_nota = :kode WHERE petugas = :ptg AND status = '0' AND jenis = 'out' AND kode_nota = ''")
   ->execute(['kode' => $tanggal, 'ptg' => $_SESSION['idp']]);

$sreq = db_fetch_all($bp, "SELECT i.id_inout, i.id_barang, i.kode_nota, i.jml_req, i.satuan, i.keterangan, b.nama_barang 
                           FROM stok_inout i 
                           JOIN stok_barang b ON i.id_barang = b.id_barang 
                           WHERE i.jenis = 'out' AND i.status = '1' AND i.petugas = :ptg",
                     ['ptg' => $_SESSION['idp']]);

$kd = db_fetch($bp, "SELECT kode_nota FROM stok_inout WHERE jenis = 'out' AND status = '1' AND petugas = :ptg LIMIT 1",
               ['ptg' => $_SESSION['idp']]);
$jreq = count($sreq);
?>

    <!-- start: page -->

    <section class="card">
        <div class="card-body">
            <div class="invoice">
                <header class="clearfix">
                    <div class="row">
                        <div class="col-sm-6 mt-3">
                            <h4 class="h4 mt-0 mb-1 text-dark font-weight-bold">SLIP PERMOHONAN PEMAKAIAN BARANG</h4>
                            <h5 class="h5 m-0 text-dark font-weight-bold"><?= htmlspecialchars($kd['kode_nota'] ?? '') ?></h5>
                        </div>
                        <div class="col-sm-6 text-right mt-3 mb-3">
                            <address class="ib mr-5">
                            <?= htmlspecialchars($set['nama_kantor'] ?? '') ?>
                                <br/>
                                <?= htmlspecialchars($set['alamat_kantor'] ?? '') ?>
                                <br/>
                                <?= htmlspecialchars($set['telp_kantor'] ?? '') ?>
                            </address>
                            <div class="ib">
                                <img src="img/<?= htmlspecialchars($set['logo_header'] ?? '') ?>" width="150" alt="Logo" />
                            </div>
                        </div>
                    </div>
                </header>
                <div class="bill-info">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="bill-data">
                                <p class="mb-0">
                                    <span class="text-dark">Seksi :</span>
                                    <span class="value"><?= htmlspecialchars($sek['bagian'] ?? '') ?></span>
                                </p>
                                <p class="mb-0">
                                    <span class="text-dark">Pemohon:</span>
                                    <span class="value"><?= htmlspecialchars($_SESSION['namap']) ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            
                <table class="table table-hover table-responsive-md invoice-items">
                   
<?php if ($jreq > 0): ?>
    <thead>
        <tr class="text-dark">
            <th class="font-weight-semibold">No</th>
            <th class="font-weight-semibold">Nama Barang</th>
            <th class="text-center font-weight-semibold">Jumlah Permintaan</th>
            <th class="text-center font-weight-semibold">Satuan</th>
            <th class="text-center font-weight-semibold">Keterangan</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($sreq as $req): ?>
        <tr class="text-dark">
            <td class="font-weight-semibold"><?= $i ?></td>
            <td class="font-weight-semibold"><?= htmlspecialchars($req['nama_barang']) ?></td>
            <td class="text-center font-weight-semibold"><?= $req['jml_req'] ?></td>
            <td class="text-center font-weight-semibold"><?= htmlspecialchars($req['satuan']) ?></td>
            <td class="text-center font-weight-semibold"><?= htmlspecialchars($req['keterangan']) ?></td>
        </tr>
    <?php $i++; endforeach; ?>
    </tbody>
</table>
</div>

<div class="text-right mr-4">
    <a href="?p=permintaan" class="btn btn-success ml-3"><i class="fas fa-list"></i> Daftar Permintaan</a>
    <a onclick="popUp('cetak/cetakslip.php?kode=<?= htmlspecialchars($kd['kode_nota'] ?? '') ?>')" class="btn btn-default ml-3" style="cursor:pointer;"><i class="fa fa-print"></i> Cetak Slip</a>
</div>
<?php else: ?>
    <tbody></tbody>
</table>
</div>
<div class="alert alert-warning mt-3">
    <i class="fas fa-inbox mr-1"></i> Data tidak ditemukan / keranjang masih kosong.
</div>
<div class="text-right mr-4">
    <a href="?p=stok" class="btn btn-primary ml-3"><i class="fas fa-list"></i> Daftar Barang</a>
</div>
<?php endif; ?>
            
        </div>
    </section>

    <!-- end: page -->