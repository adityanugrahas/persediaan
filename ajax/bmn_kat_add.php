<?php
include("../global/koneksi.php");
$tanggal=date("Y-m-d H:i:s");
?>
<div id="custom-content" class="modal-block modal-block-md">
    <section class="card">
                        <header class="card-header">
                            <div class="card-actions">
                                <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                            </div>

                            <h2 class="card-title">Tambah Kategori</h2>
                        </header>
                        <div class="card-body">
                        <form id='bmn_kat_add' method='post' action='?p=proadd&tab=bmn_kat_add' enctype='multipart/form-data' class='form-horizontal mb-lg'>	
                        <input type="hidden" name="pemroses" value="<?php echo"$pemroses";?>">
                <div class="form-group row">
                    
                    <label class="col-lg-4 control-label text-lg-right pt-2" for="inputDefault">Nama</label>
                    <div class="col-lg-8">
                        <input type='text' name='nama_kat' value="" class='form-control' placeholder='Nama Kategori Barang' required/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2" for="nip">Keterangan</label>
                    <div class="col-lg-8">
                    <input type='text' name='ket' class='form-control' placeholder='Keterangan' required/>
                    </div>
                </div>
                <hr>
                <footer class='panel-footer'>
                        <div class='col-md-12 text-right'>
                            <button class='btn btn-default modal-dismiss'>Batal</button>
                            <button class='btn btn-success modal-submit'><i class="fa fa-save"></i> Simpan</button>
                            
                        </div>
                </footer>	

                
            </form>
        </div>
    </section>
</div>