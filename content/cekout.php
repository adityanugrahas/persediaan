<?php
$tanggal = date("Ymd");
$sek = db_fetch($bp, "SELECT bagian FROM bagian WHERE id_seksi = :seksi", ['seksi' => $_SESSION['seksip']]);
?>

    <!-- start: page -->

    <section class="card">
        <div class="card-body">
            <div class="invoice">
                <header class="clearfix">
                    <div class="row">
                        <div class="col-sm-6 mt-3">
                            <h4 class="h4 mt-0 mb-1 text-dark font-weight-bold">SLIP PERMOHONAN PEMAKAIAN BARANG</h4>
                            <h5 class="h5 m-0 text-dark font-weight-bold"><?= htmlspecialchars($tanggal) ?></h5>
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
                   
<?php
$i = 1;
$sreq = db_fetch_all($bp, "SELECT i.id_inout, i.id_barang, i.jml_req, i.satuan, i.keterangan, b.nama_barang 
                           FROM stok_inout i 
                           JOIN stok_barang b ON i.id_barang = b.id_barang 
                           WHERE i.jenis = 'out' AND i.status = '0' AND i.petugas = :ptg",
                     ['ptg' => $_SESSION['idp']]);
$jreq = count($sreq);
if ($jreq > 0):
?>
     <thead>
        <tr class="text-dark">
            <th class="font-weight-semibold">No</th>
            <th class="font-weight-semibold">Nama Barang</th>
            <th class="text-center font-weight-semibold">Jumlah Permintaan</th>
            <th class="text-center font-weight-semibold">Satuan</th>
            <th class="text-center font-weight-semibold">Keterangan</th>
            <th class="text-center font-weight-semibold">Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($sreq as $req): ?>
        <tr class='text-dark'>
            <td class='font-weight-semibold'><?= $i ?></td>
            <td class='font-weight-semibold'><?= htmlspecialchars($req['nama_barang']) ?></td>
            <td class='text-center font-weight-semibold'><?= $req['jml_req'] ?></td>
            <td class='text-center font-weight-semibold'><?= htmlspecialchars($req['satuan']) ?></td>
            <td class='text-center font-weight-semibold'><?= htmlspecialchars($req['keterangan']) ?></td>
            <td class='text-center font-weight-semibold'>
                <a href='ajax/delete.php?d=cart&id=<?= htmlspecialchars($req['id_inout']) ?>' class='simple-ajax-modal btn btn-xs btn-danger' title='Hapus dari Keranjang'><i class='fa fa-trash'></i></a>
                <a href='ajax/edit_cart.php?d=cart&id=<?= htmlspecialchars($req['id_inout']) ?>' class='simple-ajax-modal btn btn-xs btn-warning' title='Edit'><i class='fa fa-edit'></i></a>
            </td>
        </tr>
    <?php $i++; endforeach; ?>
</tbody>
</table>
</div>

<div class='text-right mr-4'>
<a href='?p=ajukan' class='btn btn-primary ml-3'><i class='fas fa-check'></i> Ajukan</a>
</div>
<?php else: ?>
    <tbody></tbody>
</table>
</div>
<div class="alert alert-warning mt-3"><i class="fas fa-inbox mr-1"></i> Data tidak ditemukan / keranjang masih kosong.</div>
<div class='text-right mr-4'>
    <a href='?p=stok' class='btn btn-primary ml-3'><i class='fas fa-list'></i> Daftar Barang</a>
    <a href='?p=permintaan' class='btn btn-success ml-3'><i class='fas fa-check'></i> Daftar Permintaan</a>
</div>
<?php endif; ?>
            
        </div>
    </section>

    <!-- end: page -->