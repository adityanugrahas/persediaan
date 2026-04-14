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
                        <label>Logo Header</label>
                        <div class="mb-2">
                            <img src="img/<?= htmlspecialchars($set['logo_header']) ?>" height="100px" class="rounded shadow-sm">
                        </div>
                        <input name="logo_baru" type="file" class="form-control input-lg" />
                        <span class="help-block">Abaikan jika tidak ganti logo.</span>
                    </div>
                    <div class="form-group mb-lg">
                        <label>Title Header</label>
                        <input name="title" type="text" class="form-control input-lg" value="<?= htmlspecialchars($set['title_head']) ?>" required/>
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