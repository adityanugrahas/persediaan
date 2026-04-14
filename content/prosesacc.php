<?php
$kode = htmlspecialchars($_REQUEST["kode"]);
$req = db_fetch($bp, "SELECT * FROM stok_inout WHERE kode_nota = :kode LIMIT 1", ['kode' => $kode]);
$bagian = db_fetch($bp, "SELECT bagian FROM bagian WHERE id_seksi = :id", ['id' => $req['id_seksi']]);
$petugas = db_fetch($bp, "SELECT nama FROM petugas WHERE id_petugas = :id", ['id' => $req['petugas']]);
?>
<!-- start: page -->
<section class="card">
    <div class="card-body">
        <div class="invoice">
            <header class="clearfix">
                <div class="row">
                    <div class="col-sm-6 mt-3">
                        <h4 class="h4 mt-0 mb-1 text-dark font-weight-bold">PERMOHONAN PEMAKAIAN BARANG</h4>
                    </div>
                    <div class="col-sm-6 text-right mt-3 mb-3">
                        <address class="ib mr-5">
                        <?= htmlspecialchars($set['nama_kantor'] ?? '') ?>
                            <br/><?= htmlspecialchars($set['alamat_kantor'] ?? '') ?>
                            <br/><?= htmlspecialchars($set['telp_kantor'] ?? '') ?>
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
                            <p class="mb-0"><span class="text-dark">Seksi :</span> <span class="value"><?= htmlspecialchars($bagian['bagian'] ?? '') ?></span></p>
                            <p class="mb-0"><span class="text-dark">Pemohon:</span> <span class="value"><?= htmlspecialchars($petugas['nama'] ?? '') ?></span></p>
                            <p class="mb-0"><span class="text-dark"><b><?= htmlspecialchars($req['kode_nota']) ?> - <?= htmlspecialchars($req['tgl']) ?></b></span></p>
                        </div>
                    </div>
                </div>
            </div>
        
    <table class="table table-sm mb-0 table-bordered table-hover">
                   
<?php
$i = 1;
$items = db_fetch_all($bp, "SELECT i.id_inout, i.id_barang, i.jml_req, i.satuan, i.keterangan, b.nama_barang 
                            FROM stok_inout i JOIN stok_barang b ON i.id_barang = b.id_barang 
                            WHERE i.jenis = 'out' AND i.status = '1' AND i.kode_nota = :kode",
                      ['kode' => $kode]);
$jreq = count($items);
if ($jreq > 0):
?>
     <thead>
        <tr class="text-dark">
            <th width='5%' class="font-weight-semibold">No</th>
            <th class="font-weight-semibold">Nama Barang <?= $jreq ?></th>
            <th class="text-center font-weight-semibold">Jumlah Permintaan</th>
            <th class="text-center font-weight-semibold">Jumlah Disetujui</th>
            <th class="text-center font-weight-semibold">Satuan</th>
            <th class="text-center font-weight-semibold">Keterangan</th>
            <th class="text-center font-weight-semibold">Catatan</th>
            <th class="text-center font-weight-semibold">Aksi</th>
        </tr>
    </thead>
    <tbody>
    <form method="post" action="?p=update&tab=permintaan">
    <input type="hidden" name="jum" value="<?= $jreq ?>">
    <?php foreach ($items as $re):
        $jbr = db_fetch($bp, "SELECT jumlah_stok FROM stok_barang WHERE id_barang = :id", ['id' => $re['id_barang']]);
    ?>
<input type='hidden' name='id_inout[]' value='<?= htmlspecialchars($re['id_inout']) ?>'>
<input type='hidden' name='id_barang[]' value='<?= htmlspecialchars($re['id_barang']) ?>'>
<input type='hidden' name='jumlah_stok[]' value='<?= $jbr['jumlah_stok'] ?>'>
<input type='hidden' name='nama_barang[]' value='<?= htmlspecialchars($re['nama_barang']) ?>'>
        <tr class='text-dark'>
            <td class='font-weight-semibold'><?= $i ?></td>
            <td class='font-weight-semibold'><?= htmlspecialchars($re['nama_barang']) ?></td>
            <td class='text-center font-weight-semibold'><?= $re['jml_req'] ?></td>
            <td class='text-center font-weight-semibold'>
            <input type='number' name='jml_out[]' min='0' max='<?= $jbr['jumlah_stok'] ?>' value='<?= $re['jml_req'] ?>' class='form-control'>
            </td>
            <td class='text-center font-weight-semibold'><?= htmlspecialchars($re['satuan']) ?></td>
            <td class='text-center font-weight-semibold'><?= htmlspecialchars($re['keterangan']) ?></td>
            <td class='text-center font-weight-semibold'>
                <input type='text' name='catatan[]' class='form-control' placeholder='Catatan Admin'>
            </td>
            <td class='text-center font-weight-semibold'>
                <a href='ajax/delete.php?d=cart&id=<?= htmlspecialchars($re['id_inout']) ?>' class='simple-ajax-modal btn btn-xs btn-danger' title='Hapus'><i class='fa fa-trash'></i></a>
            </td>
        </tr>
    <?php $i++; endforeach; ?>
        </tbody>
    </table>
</div>

<div class='text-right mr-4'>
<button type='submit' class='btn btn-primary ml-3'><i class='fas fa-check'></i> Konfirm</button>
</form>
</div>
<?php else: ?>
    <tbody></tbody>
    </table>
</div>
<div class="alert alert-warning mt-3"><i class="fas fa-inbox mr-1"></i> Data tidak ditemukan.</div>
<div class='text-right mr-4'>
    <a href='?p=stok' class='btn btn-primary ml-3'><i class='fas fa-list'></i> Daftar Barang</a>
</div>
<?php endif; ?>
            
        </div>
    </section>

    <!-- end: page -->