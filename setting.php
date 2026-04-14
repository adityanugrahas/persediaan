<?php
/**
 * Setting Page — Initial application setup
 * Migrated to PDO
 */
require_once("global/koneksi.php");

$stmt = $bp->query("SELECT * FROM setting ORDER BY setting_id ASC LIMIT 1");
$set  = $stmt->fetch();

if ($set) {
    header("location:index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id" class="fixed">
<head>
    <meta charset="UTF-8">
    <title>Pengaturan Aplikasi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="theme-color" content="#0a0e1a">

    <!-- Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="vendor/animate/animate.css">
    <link rel="stylesheet" href="vendor/font-awesome/css/all.min.css" />
    <link rel="stylesheet" href="vendor/magnific-popup/magnific-popup.css" />
    <link rel="stylesheet" href="vendor/pnotify/pnotify.custom.css" />

    <!-- Theme CSS -->
    <link rel="stylesheet" href="css/theme.css" />
    <link rel="stylesheet" href="css/skins/default.css" />
    <link rel="stylesheet" href="css/custom.css">

    <script src="vendor/modernizr/modernizr.js"></script>
</head>
<body>
    <main class="body-sign">
        <div class="center-sign">
            <article class="card card-sign" style="max-width: 480px;">
                <header class="card-title-sign mt-3 text-right">
                    <h1 class="title text-uppercase font-weight-bold m-0"><i class="fas fa-cog mr-2"></i>Pengaturan Awal</h1>
                </header>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle mr-1"></i>
                        <strong>Selamat Datang!</strong> Silahkan isi data pengaturan aplikasi untuk memulai.
                    </div>
                    <form method="post" action="proses/update.php?tab=setting" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <label for="nama"><i class="fas fa-building mr-1"></i> Nama Kantor</label>
                            <input name="nama" id="nama" type="text" class="form-control" placeholder="Nama instansi / kantor" required />
                        </div>
                        <div class="form-group mb-3">
                            <label for="alamat"><i class="fas fa-map-marker-alt mr-1"></i> Alamat Kantor</label>
                            <input name="alamat" id="alamat" type="text" class="form-control" placeholder="Alamat lengkap" required />
                        </div>
                        <div class="form-group mb-3">
                            <label for="telepon"><i class="fas fa-phone mr-1"></i> Telepon Kantor</label>
                            <input name="telepon" id="telepon" type="text" class="form-control" placeholder="Nomor telepon" required />
                        </div>
                        <div class="form-group mb-3">
                            <label for="email"><i class="fas fa-envelope mr-1"></i> Email Kantor</label>
                            <input name="email" id="email" type="email" class="form-control" placeholder="email@kantor.go.id" required />
                        </div>
                        <div class="form-group mb-3">
                            <label for="logo"><i class="fas fa-image mr-1"></i> Logo Header</label>
                            <input name="logo" id="logo" type="file" class="form-control" accept="image/*" required />
                        </div>
                        <div class="form-group mb-4">
                            <label for="title"><i class="fas fa-heading mr-1"></i> Judul Aplikasi</label>
                            <input name="title" id="title" type="text" class="form-control" placeholder="Nama singkat untuk header" required />
                        </div>
                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                        </button>
                    </form>
                </div>
            </article>

            <footer class="text-center mt-3 mb-3">
                <p>&copy; <?= date("Y") ?> Sistem Persediaan</p>
            </footer>
        </div>
    </main>

    <!-- Vendor Scripts -->
    <script src="vendor/jquery/jquery.js"></script>
    <script src="vendor/popper/umd/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.js"></script>
    <script src="vendor/nanoscroller/nanoscroller.js"></script>
    <script src="vendor/magnific-popup/jquery.magnific-popup.js"></script>
    <script src="vendor/pnotify/pnotify.custom.js"></script>
    <script src="js/theme.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/theme.init.js"></script>
    <script src="js/examples/examples.modals.js"></script>
</body>
</html>