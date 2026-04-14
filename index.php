<?php
session_start();
require_once "global/koneksi.php";

// Redirect if already logged in
if (!empty($_SESSION["statep"]) && $_SESSION["statep"] === "login") {
    $level = $_SESSION["levelp"];
    if ($level === "adm") header("location:adm.php");
    elseif ($level === "opr") header("location:opr.php");
    elseif ($level === "su") header("location:su.php");
    exit;
}

// Check if app is set up
$stmt = $bp->query("SELECT * FROM setting ORDER BY setting_id ASC LIMIT 1");
$set = $stmt->fetch();

if (!$set) {
    header("location:setting.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id" class="fixed">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($set['title_head']) ?> — Login</title>
    <meta name="description" content="Sistem Manajemen Persediaan — <?= htmlspecialchars($set['nama_kantor']) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="theme-color" content="#0a0e1a">

    <!-- Modern Fonts -->
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
            <!-- Animated Logo -->
            <header class="logo text-center mb-4" style="animation: fadeInDown 0.6s ease-out;">
                <a href="/">
                    <img src="img/<?= htmlspecialchars($set['logo_header']) ?>" alt="Logo <?= htmlspecialchars($set['nama_kantor']) ?>" style="max-height: 80px;" />
                </a>
                <p class="mt-2" style="color: var(--text-muted); font-size: 0.8rem; letter-spacing: 0.05em; text-transform: uppercase;">
                    Sistem Manajemen Persediaan
                </p>
            </header>

            <article class="card card-sign" style="animation: fadeInUp 0.6s ease-out;">
                <header class="card-title-sign mt-3 text-right">
                    <h1 class="title text-uppercase font-weight-bold m-0">
                        <i class="fas fa-shield-alt mr-2"></i>Sign In
                    </h1>
                </header>
                <div class="card-body">
                    <form action="proses/login.php" method="post" autocomplete="off">
                        <?php
                        $stmt_petugas = $bp->query("SELECT users_id FROM petugas LIMIT 1");
                        $has_admin = $stmt_petugas->fetch();

                        if ($has_admin):
                            $msg = $_GET['pesan'] ?? '';
                            $add = $_GET['add'] ?? '';
                        ?>
                            <div class="alert <?php
                                if ($msg === 'gagal') echo 'alert-danger';
                                elseif ($msg === 'logout') echo 'alert-warning';
                                elseif ($add === 'ok') echo 'alert-success';
                                elseif ($add === 'fail') echo 'alert-danger';
                                else echo 'alert-info';
                            ?> py-2 mb-4" style="animation: fadeIn 0.8s ease-out;">
                                <i class="fas <?php
                                    if ($msg === 'gagal') echo 'fa-exclamation-triangle';
                                    elseif ($msg === 'logout') echo 'fa-sign-out-alt';
                                    elseif ($add === 'ok') echo 'fa-check-circle';
                                    elseif ($add === 'fail') echo 'fa-times-circle';
                                    else echo 'fa-hand-peace';
                                ?> mr-1"></i>
                                <?php
                                if ($add === 'ok') echo "<strong>Sukses!</strong> Akun Admin berhasil dibuat.";
                                elseif ($add === 'fail') echo "<strong>Gagal!</strong> Pembuatan Admin gagal.";
                                elseif ($msg === 'gagal') echo "<strong>Login Gagal!</strong> Periksa User ID dan Password.";
                                elseif ($msg === 'logout') echo "<strong>Sesi Berakhir.</strong> Anda telah keluar.";
                                else echo "<strong>Halo!</strong> Silahkan login ke akun anda.";
                                ?>
                            </div>

                            <div class="form-group mb-4">
                                <label for="user_id"><i class="fas fa-id-badge mr-1"></i> User ID / NIP</label>
                                <div class="input-group">
                                    <input name="user_id" id="user_id" type="text" class="form-control" placeholder="Masukkan User ID" required autofocus />
                                    <span class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label for="pwd"><i class="fas fa-key mr-1"></i> Password</label>
                                <div class="input-group">
                                    <input name="pwd" id="pwd" type="password" class="form-control" placeholder="Masukkan Password" required />
                                    <span class="input-group-append">
                                        <span class="input-group-text" style="cursor:pointer;" onclick="togglePwd()" id="eye-btn"><i class="fa fa-eye-slash" id="eye-icon"></i></span>
                                    </span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block btn-lg mt-3">
                                <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                            </button>

                        <?php else: ?>
                            <div class="alert alert-warning mb-4">
                                <i class="fas fa-user-plus mr-1"></i>
                                <strong>Setup Diperlukan!</strong> Akun Administrator belum tersedia. Silahkan buat akun utama.
                            </div>

                            <div class="form-group mb-3">
                                <label for="nama"><i class="fas fa-user mr-1"></i> Nama Lengkap</label>
                                <input name="nama" id="nama" type="text" class="form-control" placeholder="Nama lengkap admin" required />
                            </div>
                            <div class="form-group mb-3">
                                <label for="user_id"><i class="fas fa-id-badge mr-1"></i> User ID Baru</label>
                                <input name="user_id" id="user_id" type="text" class="form-control" placeholder="ID untuk login" required />
                            </div>
                            <div class="form-group mb-4">
                                <label for="pwd"><i class="fas fa-key mr-1"></i> Password</label>
                                <input name="pwd" id="pwd" type="password" class="form-control" placeholder="Password akun" required />
                            </div>

                            <button type="submit" class="btn btn-primary btn-block btn-lg">
                                <i class="fas fa-user-shield mr-2"></i>Buat Admin
                            </button>
                        <?php endif; ?>
                    </form>
                </div>
            </article>

            <footer class="text-center mt-4 mb-3" style="animation: fadeIn 1s ease-out;">
                <p style="color: var(--text-muted); font-size: 0.78rem;">
                    &copy; <?= date("Y") ?> <?= htmlspecialchars($set['nama_kantor']) ?>
                </p>
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

    <script>
    function togglePwd() {
        var pwd = document.getElementById('pwd');
        var icon = document.getElementById('eye-icon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            pwd.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }
    </script>
</body>
</html>
