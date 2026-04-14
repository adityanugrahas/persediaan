<div id="custom-content" class="modal-block modal-block-md">
    <section class="card">
                        <header class="card-header">
                            <div class="card-actions">
                                <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                            </div>

                            <h2 class="card-title">Tambah Data Anggaran</h2>
                        </header>
                        <div class="card-body">
                        <form id='anggaran_add' method='post' action='?p=proadd&tab=anggaran_add' enctype='multipart/form-data' class='form-horizontal mb-lg'>	
                <div class="form-group row">                    
                    <label class="col-lg-4 control-label text-lg-right pt-2" for="inputDefault">Tahun Anggaran</label>
                    <div class="col-lg-8">
                        <input type='text' name='ta'  class='form-control' placeholder='Tahun Anggaran'/>
                    </div>
                </div>

                <div class="form-group row">                    
                    <label class="col-lg-4 control-label text-lg-right pt-2" for="inputDefault">Akun</label>
                    <div class="col-lg-8">
                        <input type='text' name='akun'  class='form-control' placeholder='Akun / Peruntukan Anggaran'/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2" for="nip">Keterangan</label>
                    <div class="col-lg-8">
                    <input type='text' name='ket_akun'  class='form-control' placeholder='Keterangan Akun'/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2" for="nip">Nilai Pagu</label>
                    <div class="col-lg-8">
                    <input type='text' name='pagu'  class='form-control' placeholder='Nilai Jumlah Pagu'/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 control-label text-lg-right pt-2" for="nip">Lampiran</label>
                    <div class="col-lg-8">
                    <input type='file' name='lampiran'  class='form-control' placeholder='Lampiran'/>
                    </div>
                </div>
                <hr>
                <footer class='panel-footer'>
                        <div class='col-md-12 text-right'>
                            <button type="submit" class='btn btn-success modal-submit'><i class="fa fa-edit"></i> OK </button>
                            <button class='btn btn-default modal-dismiss'>Batal</button>
                        </div>
                </footer>	

                
            </form>
        </div>
    </section>
</div>