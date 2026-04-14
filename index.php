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
    <!-- Background Decorative Elements -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <main class="body-sign">
        <div class="center-sign">
            <!-- Animated Logo & Header -->
            <header class="logo text-center mb-5" style="animation: fadeInDown 1s var(--transition-smooth);">
                <a href="/">
                    <img src="img/<?= htmlspecialchars($set['logo_header']) ?>" alt="Logo" style="max-height: 80px; filter: drop-shadow(0 0 15px var(--primary-glow));" />
                </a>
                <h1 class="mt-4" style="color: #fff; font-size: 2.2rem; font-weight: 800; letter-spacing: -0.04em; margin-bottom: 0.2rem;">
                    <?= htmlspecialchars($set['nama_kantor']) ?>
                </h1>
                <p style="color: var(--text-muted); font-size: 0.8rem; letter-spacing: 0.3em; text-transform: uppercase; font-weight: 600;">
                    Inventory Intelligence System
                </p>
            </header>

            <article class="card card-sign" style="animation: fadeInUp 1s var(--transition-smooth); border-radius: var(--radius-lg) !important; border: 1px solid var(--glass-border-bright) !important;">
                <header class="card-title-sign mt-4 text-center">
                    <div style="background: var(--glass-medium); padding: 10px 24px; border-radius: 30px; display: inline-flex; align-items:center; border: 1px solid var(--glass-border);">
                        <i class="fas fa-shield-alt mr-2 text-primary"></i>
                        <span style="font-size: 0.7rem; font-weight: 800; letter-spacing: 0.08em; color: #fff;">SECURE GATEWAY</span>
                    </div>
                </header>
                <div class="card-body p-5">
                    <form action="proses/login.php" method="post" autocomplete="off">
                        <?php
                        $stmt_petugas = $bp->query("SELECT users_id FROM petugas LIMIT 1");
                        $has_admin = $stmt_petugas->fetch();

                        if ($has_admin):
                            $msg = $_GET['pesan'] ?? '';
                            $add = $_GET['add'] ?? '';
                        ?>
                            <?php if($msg || $add): ?>
                            <div class="alert badge-info py-3 mb-4 text-center" style="border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.2);">
                                <small style="font-weight: 600; color:#fff; text-transform: uppercase; letter-spacing: 0.05em;">
                                    <?php
                                    if ($add === 'ok') echo "Administrator Profile Created";
                                    elseif ($add === 'fail') echo "Database Integration Error";
                                    elseif ($msg === 'gagal') echo "Invalid Signature or Key";
                                    elseif ($msg === 'logout') echo "Security Session Terminated";
                                    else echo "Authentication Required";
                                    ?>
                                </small>
                            </div>
                            <?php endif; ?>

                            <div class="form-group mb-4">
                                <label class="text-muted small font-weight-bold mb-3 text-uppercase" style="letter-spacing: 0.1em; opacity: 0.8; display: block;">Authorized Identity</label>
                                <div class="input-group input-group-lg">
                                    <input name="user_id" id="user_id" type="text" class="form-control" placeholder="User ID" required autofocus />
                                </div>
                            </div>

                            <div class="form-group mb-5">
                                <label class="text-muted small font-weight-bold mb-3 text-uppercase" style="letter-spacing: 0.1em; opacity: 0.8; display: block;">Security Access Key</label>
                                <div class="input-group input-group-lg">
                                    <input name="pwd" id="pwd" type="password" class="form-control" placeholder="········" required />
                                    <span class="input-group-append">
                                        <button class="input-group-text" type="button" onclick="togglePwd()" id="eye-btn"><i class="fa fa-eye-slash" id="eye-icon" style="opacity:0.6;"></i></button>
                                    </span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block btn-lg" style="height: 60px; font-weight: 700; font-size: 1rem;">
                                <i class="fas fa-sign-in-alt mr-2"></i> AUTHORIZE SESSION
                            </button>

                        <?php else: ?>
                            <div class="alert alert-warning mb-4" style="border-radius: 12px; background: rgba(245, 158, 11, 0.1); border: 1px solid var(--warning);">
                                <i class="fas fa-rocket mr-2"></i>
                                <span style="font-weight: 600;">System Initialization Required.</span> Create your root administrator profile.
                            </div>

                            <div class="form-group mb-3">
                                <input name="nama" type="text" class="form-control" placeholder="Full Administrative Name" required />
                            </div>
                            <div class="form-group mb-3">
                                <input name="user_id" type="text" class="form-control" placeholder="Root Username" required />
                            </div>
                            <div class="form-group mb-4">
                                <input name="pwd" type="password" class="form-control" placeholder="Root Security Key" required />
                            </div>

                            <button type="submit" class="btn btn-primary btn-block btn-lg">
                                <i class="fas fa-cog mr-2"></i> INITIALIZE SYSTEM
                            </button>
                        <?php endif; ?>
                    </form>
                </div>
            </article>

            <footer class="text-center mt-4">
                <p style="color: var(--text-muted); font-size: 0.8rem; font-weight: 500;">
                    &copy; <?= date("Y") ?> <span style="color: #fff;"><?= htmlspecialchars($set['nama_kantor']) ?></span>
                </p>
                <div class="mt-2">
                    <span class="badge badge-info" style="opacity: 0.5;">v2.0 Glassmorphism Edition</span>
                </div>
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
