<?php
$kode = date("ymd.Hi");
$tgl_ok = date("Y-m-d H:i:s");
$sek = db_fetch($bp, "SELECT bagian FROM bagian WHERE id_seksi = :seksi", ['seksi' => $_SESSION['seksip']]);
?>

    <!-- start: page -->

    <section class="card">
        <div class="card-body">
            <div class="">
                <header class="clearfix">
                    <div class="row">
                        <div class="col-sm-6 mt-3">
                            <h4 class="h4 mt-0 mb-1 text-dark font-weight-bold">Detail Penambahan Stok</h4>
                            <h5 class="h5 m-0 text-dark font-weight-bold"><?= htmlspecialchars($kode) ?></h5>
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
<form method="post" action="?p=update&tab=stok_tambah" enctype='multipart/form-data'>
    
                <div class="bill-info">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="bill-data">
                                
                            </div>
                        </div>
                    </div>
                </div>
            
                <table class="table table-hover table-bordered table-responsive-md table-sm mb-0">
                   
<?php
$i = 1;
$sreq = db_fetch_all($bp, "SELECT i.id_inout, i.id_barang, i.jml_in, i.satuan, i.harga, i.keterangan, b.nama_barang, b.jumlah_stok 
                           FROM stok_inout i 
                           JOIN stok_barang b ON i.id_barang = b.id_barang 
                           WHERE i.jenis = 'in' AND i.status = '0' AND i.id_seksi = :seksi",
                     ['seksi' => $_SESSION['seksip']]);
$jreq = count($sreq);
if ($jreq > 0):
?>
     <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Jumlah Stok</th>
            <th class="text-center">Jumlah Penambahan</th>
            <th class="text-center">Satuan</th>
            <th class="text-center">Harga Satuan</th>
            <th class="text-center">Jumlah</th>
            <th class="text-center">Keterangan</th>
            <th class="text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>

    <?php
    $total = 0;
    foreach ($sreq as $req):
        $jumlah2 = $req["jml_in"] * $req["harga"];
        $harga = number_format($req["harga"]);
        $jumlah = number_format($jumlah2);
?>
<input type='hidden' name='jum' value='<?= $jreq ?>'>
<input type='hidden' name='id_inout[]' value='<?= htmlspecialchars($req['id_inout']) ?>'>
<input type='hidden' name='id_barang[]' value='<?= htmlspecialchars($req['id_barang']) ?>'>
<input type='hidden' name='kode' value='<?= htmlspecialchars($kode) ?>'>
<input type='hidden' name='tgl_ok' value='<?= htmlspecialchars($tgl_ok) ?>'>
<input type='hidden' name='jml_in[]' value='<?= $req['jml_in'] ?>'>
<input type='hidden' name='jml_stok[]' value='<?= $req['jumlah_stok'] ?>'>
<input type='hidden' name='satuan[]' value='<?= htmlspecialchars($req['satuan']) ?>'>
<input type='hidden' name='ket[]' value='<?= htmlspecialchars($req['keterangan']) ?>'>

    <tr>
        <td><?= $i ?></td>
        <td><?= htmlspecialchars($req['nama_barang']) ?></td>
        <td><?= $req['jumlah_stok'] ?></td>
        <td class='text-center'><?= $req['jml_in'] ?></td>
        <td class='text-center'><?= htmlspecialchars($req['satuan']) ?></td>
        <td class='text-center'><?= $harga ?></td>
        <td class='text-center'><?= $jumlah ?></td>
        <td class='text-center'><?= htmlspecialchars($req['keterangan']) ?></td>
        <td class='text-center'>
            <a href='ajax/delete.php?d=cart&id=<?= htmlspecialchars($req['id_inout']) ?>' class='simple-ajax-modal btn btn-xs btn-danger' title='Hapus dari Keranjang'><i class='fa fa-trash'></i></a>
            <a href='ajax/edit_cart.php?d=cart&id=<?= htmlspecialchars($req['id_inout']) ?>' class='simple-ajax-modal btn btn-xs btn-warning' title='Edit'><i class='fa fa-edit'></i></a>
        </td>
    </tr>
<?php
        $i++;
        $total += $jumlah2;
    endforeach;
    $total2 = number_format($total);
?>
<input type='hidden' name='total' value='<?= $total ?>'>
<tr style='font-weight:bold;background-color:#cfcfcf'><td colspan='6' class='text-center'>Total</td><td colspan='1' class='text-center'><?= $total2 ?></td><td colspan='2'></td></tr>
</tbody>
</table>
</div><hr>
<div class="alert alert-info nomargin">
        <table width="100%" cellspacing="5" border="0" cellpadding="5" style="border:0px solid #000">
            <tr><td>Tanggal</td><td>: <?= htmlspecialchars($tgl_ok) ?></td>
                <td>Keterangan</td><td><input type="text" name="keterangan" class="form-control" placeholder="Keterangan Transaksi" required></td>
            </tr>
            <tr><td>Sumber Dana</td><td>: 
            <?php
$anggarans = db_fetch_all($bp, "SELECT * FROM anggaran WHERE status = 1");
if (count($anggarans) > 0):
?>
    <select name='sumber_dana' class='btn btn-sm btn-dark' required>
    <option value='' class='btn btn-warning'>-</option>
<?php foreach ($anggarans as $d): ?>
    <option value='<?= htmlspecialchars($d['id_anggaran']) ?>' class='btn btn-primary'><?= htmlspecialchars($d['akun_anggaran']) ?> - <?= htmlspecialchars($d['ket_anggaran']) ?></option>
<?php endforeach; ?>
    </select>
<?php else: ?>
    <input type='hidden' name='sumber_dana' value=''>
    <b><span class='highlight'>ANGGARAN BELUM TERSEDIA</span> <a href='?p=anggaran'> Klik Disini Untuk Tambah Data Anggaran </a></b>
<?php endif; ?>
            </td>
            <td>Lampiran</td><td><input type="file" name="lampiran"></td>
            </tr>
        </table>
    </div>
<div class='text-right mr-4'><hr>
<button type='submit' class='btn btn-primary ml-3'><i class='fas fa-download'></i> Proses</button>
</div>
</form>
<?php else: ?>
    <tbody></tbody>
    </table>
</div>
<div class="alert alert-warning mt-3"><i class="fas fa-inbox mr-1"></i> Data tidak ditemukan / keranjang masih kosong.</div>
<div class='text-right mr-4'>
    <a href='?p=stok' class='btn btn-primary ml-3'><i class='fas fa-list'></i> Daftar Barang</a>
</div>
<?php endif; ?>
            
        </div>
    </section>

    <!-- end: page -->