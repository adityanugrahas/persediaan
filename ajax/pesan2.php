<?php
session_start();
include("../global/koneksi.php");

$id = $_SESSION["seksip"] ?? '';
$seksi = db_fetch($bp, "SELECT bagian FROM bagian WHERE id_seksi = :id", ['id' => $id]);
?>

<div id="modalHeaderColorPrimary" class="modal-block modal-header-color modal-block-danger">
    <section class="card">
        <header class="card-header">
            <div class="card-actions">
                <button class='btn btn-dark btn-sm modal-dismiss'>X</button>
            </div>
            <h2 class="card-title">Form Pemesanan Barang Baru</h2>
            <span class='text-white-50'>Pemesanan Item Barang yang belum tersedia di sistem.</span>
        </header>
        <div class="card-body">
            <form id='order_new_form' method='POST' action='?p=proadd&tab=pesan' enctype='multipart/form-data' class='form-horizontal'>
                <input type='hidden' name='jenis' value='out'>
                <input type='hidden' name='status' value='0'>	
                
                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Nama Barang</label>
                    <div class="col-lg-8">
                        <input type='text' name='nama_barang' class='form-control' placeholder='Nama Barang' required>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Deskripsi Barang</label>
                    <div class="col-lg-8">
                        <input type='text' name='ket_barang' class='form-control' placeholder='Deskripsi / Keterangan Barang' required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Jumlah & Satuan</label>
                    <div class="col-lg-4">
                        <input type='number' name='jum_pes' min='1' class='form-control' placeholder='Jumlah' required/>
                    </div>
                    <div class="col-lg-4">
                        <input type='text' name="satuan" class='form-control' placeholder='Satuan (Pcs, Box, dll)' required/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Keterangan Pemakaian</label>
                    <div class="col-lg-8">
                        <input type='text' name='keterangan' class='form-control' placeholder="Tujuan penggunaan">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Lampiran (Foto/Spek)</label>
                    <div class="col-lg-8">
                        <input type='file' name='lampiran' class='form-control'>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Unit Kerja</label>
                    <div class="col-lg-8">
                        <input type='text' value="<?= htmlspecialchars($seksi['bagian'] ?? '') ?>" class='form-control' disabled>
                        <input type='hidden' name='id_seksi' value="<?= htmlspecialchars($_SESSION['seksip'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">Pemesan</label>
                    <div class="col-lg-8">
                        <input type='text' value="<?= htmlspecialchars($_SESSION['namap'] ?? '') ?>" class='form-control' disabled>
                        <input type='hidden' name='id_petugas' value="<?= htmlspecialchars($_SESSION['idp'] ?? '') ?>">
                    </div>
                </div>
                
                <hr>
                <div class='text-right'>
                    <button type="button" class='btn btn-default modal-dismiss'>Batal</button>
                    <button type="submit" class='btn btn-danger'><i class="fa fa-shopping-cart"></i> Pesan Sekarang</button>
                </div>
            </form>
        </div>
    </section>
</div>