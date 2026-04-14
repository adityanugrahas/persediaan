<?php
$set = db_fetch($bp, "SELECT * FROM setting ORDER BY setting_id ASC LIMIT 1");
?>
<!-- start: page -->
<section class="body-sign">
    <div class="center-sign">
        <div class="panel card-sign">
            <div class="card-title-sign mt-3 text-right">
                <h2 class="title text-uppercase font-weight-bold m-0"><i class="fas fa-cog mr-1"></i> Setting</h2>
            </div>
            <div class="card-body">
                <form method="post" action="?p=update&tab=setting2" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($set['setting_id']) ?>">
                    <input type="hidden" name="logo_lama" value="<?= htmlspecialchars($set['logo_header']) ?>">
                    <input type="hidden" name="favicon_lama" value="<?= htmlspecialchars($set['favicon'] ?? 'favicon.png') ?>">
                    
                    <div class="form-group mb-lg">
                        <label>Nama Kantor</label>
                        <input name="nama" type="text" class="form-control input-lg" value="<?= htmlspecialchars($set['nama_kantor']) ?>" required/>
                    </div>
                    <div class="form-group mb-lg">
                        <label>Alamat Kantor</label>
                        <input name="alamat" type="text" class="form-control input-lg" value="<?= htmlspecialchars($set['alamat_kantor']) ?>" required/>
                    </div>
                    <div class="form-group mb-lg">
                        <label>Telepon Kantor</label>
                        <input name="telepon" type="text" class="form-control input-lg" value="<?= htmlspecialchars($set['telp_kantor']) ?>" required/>
                    </div>
                    <div class="form-group mb-lg">
                        <label>Email Kantor</label>
                        <input name="email" type="email" class="form-control input-lg" value="<?= htmlspecialchars($set['email_kantor'] ?? '') ?>" required/>
                    </div>
                    <div class="form-group mb-lg">
                        <label>Title Header</label>
                        <input name="title" type="text" class="form-control input-lg" value="<?= htmlspecialchars($set['title_head']) ?>" required/>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-lg">
                                <label>Logo Header</label>
                                <div class="mb-3 p-3 bg-dark-subtle border-radius-md text-center" style="background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border);">
                                    <img src="img/<?= htmlspecialchars($set['logo_header']) ?>" height="60px" class="shadow-sm logo-preview">
                                </div>
                                <input name="logo_baru" type="file" class="form-control input-lg" />
                                <span class="help-block small text-muted">Abaikan jika tidak ganti logo.</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-lg">
                                <label>Favicon</label>
                                <div class="mb-3 p-3 bg-dark-subtle border-radius-md text-center" style="background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border);">
                                    <img src="img/<?= htmlspecialchars($set['favicon'] ?? 'favicon.png') ?>" height="40px" class="shadow-sm favicon-preview">
                                </div>
                                <input name="favicon_baru" type="file" class="form-control input-lg" />
                                <span class="help-block small text-muted">Abaikan jika tidak ganti favicon.</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- end: page -->