<?php
session_start();

// Simplified redirect logic
if (!empty($_SESSION["statep"]) && $_SESSION["statep"] == "login") {
    switch ($_SESSION["levelp"]) {
        case 'adm': header("location:adm.php"); exit;
        case 'opr': header("location:opr.php"); exit;
        case 'su':  header("location:su.php");  exit;
    }
}

include("global/koneksi.php");

$set = db_fetch($bp, "SELECT * FROM setting ORDER BY setting_id ASC LIMIT 1");

if (!$set) {
    header("location:setting.php");
    exit;
}
?>
<!doctype html>
<html class="fixed">
	<head>
		<title><?= htmlspecialchars($set['title_head']) ?></title>

		<!-- Basic -->
		<meta charset="UTF-8">
		<meta name="keywords" content="<?= htmlspecialchars($set['nama_kantor']) ?>" />
		<meta name="description" content="<?= htmlspecialchars($set['nama_kantor']) ?>">
		<meta name="author" content="Persediaan System">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="vendor/animate/animate.css">
		<link rel="stylesheet" href="vendor/font-awesome/css/all.min.css" />
		<link rel="stylesheet" href="vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />

		<!-- Specific Page Vendor CSS -->
		<link rel="stylesheet" href="vendor/pnotify/pnotify.custom.css" />
		<link rel="stylesheet" href="vendor/select2/css/select2.css" />
		<link rel="stylesheet" href="vendor/select2-bootstrap-theme/select2-bootstrap.min.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="css/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="css/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="css/custom.css">

		<!-- Head Libs -->
		<script src="vendor/modernizr/modernizr.js"></script>

	</head>
	
	<body>
		<!-- start: page -->
		<section class="body-sign">
			<div class="center-sign">
				<a href="/" class="logo float-left">
					<img src="img/<?= htmlspecialchars($set['logo_header']) ?>" height="54" alt="Logo" />
				</a>

				<div class="panel card-sign shadow-lg">
					<div class="card-title-sign mt-3 text-right">
						<h2 class="title text-uppercase font-weight-bold m-0"><i class="fas fa-user mr-1"></i> Sign In</h2>
					</div>
					<div class="card-body">
						<form action="proses/login.php" method="post">
<?php
$user_exists = db_fetch_column($bp, "SELECT COUNT(*) FROM petugas");
if ($user_exists > 0):
?>
							<div class="alert alert-success">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
<?php
    $add = $_REQUEST["add"] ?? '';
    $pesan = $_REQUEST["pesan"] ?? '';

    if ($add == "ok") {
        echo "<strong>Selamat Datang!</strong> Akun Admin Telah Berhasil Dibuat <a href='' class='alert-link'>Silahkan Login</a>.";
    } elseif ($add == "fail") {
        echo "<strong>Maaf!</strong> Akun Admin Gagal Dibuat.";
    } elseif ($pesan == "gagal") {
        echo "<strong>Maaf! Login Gagal</strong> <br>Silahkan periksa User ID dan Password Anda.";
    } elseif ($pesan == "logout") {
        echo "<strong>Logout Berhasil</strong> <br>Silahkan masuk kembali untuk melanjutkan.";
    } else {
        echo "<strong>Selamat Datang!</strong> Silahkan Log In.";
    }
?>
							</div>

							<div class="form-group mb-lg">
								<label>User ID / NIP</label>
								<div class="input-group input-group-icon">
									<input name="user_id" type="text" class="form-control input-lg" required />
									<span class="input-group-append">
										<span class="input-group-text">
											<i class="fa fa-user"></i>
										</span>
									</span>
								</div>
							</div>

							<div class="form-group mb-lg">
								<div class="clearfix">
									<label class="float-left">Password</label>
								</div>
								<div class="input-group input-group-icon">
									<input name="pwd" type="password" class="form-control input-lg" required />
									<span class="input-group-append">
										<span class="input-group-text">
											<i class="fa fa-lock"></i>
										</span>
									</span>
								</div>
							</div>
<?php
    $submit_text = "Masuk";
else:
?>
							<div class="alert alert-dark">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<strong>Selamat Datang!</strong> Akun Admin Belum Dibuat. <br>Silahkan membuat Akun Admin Pertama Anda.
							</div>

							<div class="form-group mb-lg">
								<label>Nama Lengkap</label>
								<div class="input-group input-group-icon">
									<input name="nama" type="text" class="form-control input-lg" required />
									<span class="input-group-append">
										<span class="input-group-text">
											<i class="fa fa-user"></i>
										</span>
									</span>
								</div>
							</div>

							<div class="form-group mb-lg">
								<label>User ID / NIP</label>
								<div class="input-group input-group-icon">
									<input name="user_id" type="text" class="form-control input-lg" required />
									<span class="input-group-append">
										<span class="input-group-text">
											<i class="fa fa-key"></i>
										</span>
									</span>
								</div>
							</div>

							<div class="form-group mb-lg">
								<label>Password</label>
								<div class="input-group input-group-icon">
									<input name="pwd" type="password" class="form-control input-lg" required />
									<span class="input-group-append">
										<span class="input-group-text">
											<i class="fa fa-lock"></i>
										</span>
									</span>
								</div>
							</div>
<?php
    $submit_text = "Daftarkan Admin";
endif;
?>
							<div class="row">
								<div class="col-sm-12 text-right">
									<button type="submit" class="btn btn-primary btn-block btn-lg"><?= $submit_text ?></button>
								</div>
							</div>
						</form>
					</div>
				</div>

				<p class="text-center text-muted mt-3 mb-3">&copy; Copyright <?= date('Y') ?>. <?= htmlspecialchars($set['nama_kantor']) ?></p>
			</div>
		</section>
		<!-- end: page -->

		<!-- Vendor -->
		<script src="vendor/jquery/jquery.js"></script>
		<script src="vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="vendor/popper/umd/popper.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.js"></script>
		<script src="vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="vendor/common/common.js"></script>
		<script src="vendor/nanoscroller/nanoscroller.js"></script>
		<script src="vendor/magnific-popup/jquery.magnific-popup.js"></script>
		<script src="vendor/jquery-placeholder/jquery.placeholder.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="js/theme.js"></script>
		<script src="js/custom.js"></script>
		<script src="js/theme.init.js"></script>

		<!-- Specific Page Vendor JS -->
		<script src="vendor/pnotify/pnotify.custom.js"></script>
		<script src="vendor/select2/js/select2.js"></script>
		<script src="js/examples/examples.modals.js"></script>
	</body>
</html>