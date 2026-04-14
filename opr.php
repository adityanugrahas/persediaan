<?php
session_start();
$level = $_SESSION["levelp"] ?? '';

if (($_SESSION["statep"] ?? '') === "login" && $level === "adm") {
    header("location:adm.php");
    exit;
}
if (($_SESSION["statep"] ?? '') === "login" && $level === "su") {
    header("location:su.php");
    exit;
}

if (($_SESSION["statep"] ?? '') === "login" && $level === "opr") {
    require_once("global/koneksi.php");

    // Fetch settings
    $set = db_fetch($bp, "SELECT * FROM setting ORDER BY setting_id ASC LIMIT 1");

    // Fetch user department
    $sek = db_fetch($bp, "SELECT bagian FROM bagian WHERE id_seksi = :seksi", ['seksi' => $_SESSION['seksip']]);
?>
<!DOCTYPE html>
<html lang="id" class="fixed has-top-menu">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($set['title_head'] ?? 'Persediaan') ?> — Operator</title>
    <meta name="description" content="<?= htmlspecialchars($set['nama_kantor'] ?? '') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="theme-color" content="#0a0e1a">

    <!-- Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.css" />
    <link rel="shortcut icon" href="https://upload.wikimedia.org/wikipedia/commons/0/0a/Logo_Ditjen_Imigrasi.webp" type="image/x-icon" />
    <title>Sistem Persediaan - Operator</title>
    <link rel="stylesheet" href="vendor/animate/animate.css">
    <link rel="stylesheet" href="vendor/font-awesome/css/all.min.css" />
    <link rel="stylesheet" href="vendor/magnific-popup/magnific-popup.css" />
    <link rel="stylesheet" href="vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />
    <link rel="stylesheet" href="vendor/jquery-ui/jquery-ui.css" />
    <link rel="stylesheet" href="vendor/jquery-ui/jquery-ui.theme.css" />
    <link rel="stylesheet" href="vendor/bootstrap-multiselect/css/bootstrap-multiselect.css" />
    <link rel="stylesheet" href="vendor/morris/morris.css" />
    <link rel="stylesheet" href="vendor/select2/css/select2.css" />
    <link rel="stylesheet" href="vendor/select2-bootstrap-theme/select2-bootstrap.min.css" />
    <link rel="stylesheet" href="vendor/pnotify/pnotify.custom.css" />
    <link rel="stylesheet" href="vendor/datatables/media/css/dataTables.bootstrap4.css" />

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

    <section class="body">
        <!-- start: header -->
        <header class="header header-nav-menu header-nav-stripe">
            <div class="logo-container">
                <a href="opr.php" class="logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/0a/Logo_Ditjen_Imigrasi.webp" height="55" alt="Ditjen Imigrasi" />
                </a>

                <form action="" method="get" class="logo">
                    <input type="hidden" name="p" value="cari">
                    <div class="input-group">
                        <input type="text" class="form-control" name="k" id="k" placeholder="Cari Barang..." aria-label="Cari Barang">
                        <span class="input-group-append">
                            <button class="btn btn-default" type="submit"><i class="fas fa-search"></i></button>
                        </span>
                    </div>
                </form>

                <button class="btn header-btn-collapse-nav d-lg-none" data-toggle="collapse" data-target=".header-nav" aria-label="Toggle navigation">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Top navigation -->
                <nav class="header-nav-menu">
                    <?php include("global/topnav-opr.php"); ?>
                </nav>
            </div>

            <!-- start: search & user box -->
            <div class="header-right">
                <span class="separator"></span>
                <ul class="notifications">
                    <!-- NOTIFICATION BELL -->
                    <li>
                        <?php
                        $snt = db_fetch_all($bp, "SELECT n.id_notif, n.id_barang, b.nama_barang, n.keterangan
                                                   FROM notif n
                                                   JOIN stok_barang b ON n.id_barang = b.id_barang
                                                   WHERE n.id_petugas = :ptg AND n.status = '0'",
                                             ['ptg' => $_SESSION['idp']]);
                        $jpn = count($snt);
                        $notif_nt = ($jpn > 0) ? "<span class='badge'>{$jpn}</span>" : "";
                        ?>
                        <a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <?= $notif_nt ?>
                        </a>
                        <div class="dropdown-menu notification-menu">
                            <div class="notification-title">
                                <span class="float-right badge badge-default"><?= $jpn ?></span>Pemberitahuan
                            </div>
                            <div class="content">
                                <ul>
                                    <?php foreach ($snt as $nt): ?>
                                    <li>
                                        <p class='clearfix mb-1'>
                                            <a href='?p=update&tab=notifopen&idnotif=<?= htmlspecialchars($nt['id_notif']) ?>&k=<?= urlencode($nt['nama_barang']) ?>'>
                                                <span class='message float-right text-dark'><?= htmlspecialchars($nt['keterangan']) ?></span>
                                            </a>
                                        </p>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                                <hr />
                                <div class="text-right">
                                    <a href="?p=notif" class="view-more">Lihat Semua <i class='fa fa-arrow-right'></i></a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- PESANAN NOTIFICATIONS -->
                    <li>
                        <?php
                        $sps = db_fetch_all($bp, "SELECT p.id_barang, p.petugas, b.nama_barang, p.jumlah, b.satuan
                                                   FROM pesanan p
                                                   JOIN stok_barang b ON p.id_barang = b.id_barang
                                                   WHERE p.petugas = :ptg AND b.jumlah_stok = 0",
                                             ['ptg' => $_SESSION['idp']]);
                        $jps = count($sps);
                        $notif_ps = ($jps > 0) ? "<span class='badge'>{$jps}</span>" : "";
                        ?>
                        <a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
                            <i class="fas fa-comment-alt"></i>
                            <?= $notif_ps ?>
                        </a>
                        <div class="dropdown-menu notification-menu">
                            <div class="notification-title">
                                <span class="float-right badge badge-default"><?= $jps ?></span>Pesanan
                            </div>
                            <div class="content">
                                <ul>
                                    <?php foreach ($sps as $ps): ?>
                                    <li>
                                        <p class='clearfix mb-1'>
                                            <a href='?p=pesanan&pt=<?= htmlspecialchars($ps['petugas']) ?>' class='btn btn-xs message float-left text-dark'><?= htmlspecialchars($ps['nama_barang']) ?></a>
                                            <span class='message float-right text-dark'><?= $ps['jumlah'] ?> <?= htmlspecialchars($ps['satuan']) ?></span>
                                        </p>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                                <hr />
                                <div class="text-right">
                                    <a href="?p=pesanan" class="view-more">Lihat Semua <i class='fa fa-arrow-right'></i></a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- CART -->
                    <li>
                        <?php
                        $re_items = db_fetch_all($bp, "SELECT i.id_barang, i.satuan, i.jml_req, b.nama_barang
                                                        FROM stok_inout i
                                                        JOIN stok_barang b ON i.id_barang = b.id_barang
                                                        WHERE i.jenis = 'out' AND i.status = '0' AND i.petugas = :ptg",
                                                  ['ptg' => $_SESSION['idp']]);
                        $jre = count($re_items);
                        $notif_cart = ($jre > 0) ? "<span class='badge'>{$jre}</span>" : "";
                        ?>
                        <a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
                            <i class="fas fa-shopping-cart"></i>
                            <?= $notif_cart ?>
                        </a>
                        <div class="dropdown-menu notification-menu">
                            <div class="notification-title">
                                <span class="float-right badge badge-default"><?= $jre ?></span>Permintaan
                            </div>
                            <div class="content">
                                <ul>
                                    <?php foreach ($re_items as $re): ?>
                                    <li>
                                        <p class='clearfix mb-1'>
                                            <a href='?p=order' class='btn btn-xs message float-left text-dark'><?= htmlspecialchars($re['nama_barang']) ?></a>
                                            <span class='message float-right text-dark'><?= $re['jml_req'] ?> <?= htmlspecialchars($re['satuan']) ?></span>
                                        </p>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                                <hr />
                                <div class="text-right">
                                    <a href="?p=cekout" class="view-more">Ajukan <i class='fa fa-arrow-right'></i></a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <span class="separator"></span>

                <!-- Userbox -->
                <div id="userbox" class="userbox">
                    <a href="#" data-toggle="dropdown">
                        <figure class="profile-picture">
                            <img src="img/users/<?= htmlspecialchars($_SESSION['photop'] ?? 'user.png') ?>" alt="<?= htmlspecialchars($_SESSION['namap']) ?>" class="rounded-circle" />
                        </figure>
                        <div class="profile-info">
                            <span class="name"><?= htmlspecialchars($_SESSION['namap']) ?></span>
                            <span class="role"><?= htmlspecialchars($sek['bagian'] ?? '') ?></span>
                        </div>
                        <i class="fa custom-caret"></i>
                    </a>
                    <div class="dropdown-menu">
                        <ul class="list-unstyled">
                            <li class="divider"></li>
                            <li><a role="menuitem" tabindex="-1" href="?p=profil"><i class="fas fa-user"></i> Akun Saya</a></li>
                            <li><a role="menuitem" tabindex="-1" href="?p=logout"><i class="fas fa-power-off"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- end: search & user box -->
        </header>
        <!-- end: header -->

        <header class="page-header">
            <h2><?php include("global/header.php"); ?></h2>
            <div class="right-wrapper text-right">
                <ol class="breadcrumbs">
                    <li><a href="opr.php"><i class="fas fa-home"></i></a></li>
                    <li><span>Dashboard</span></li>
                </ol>
                <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fas fa-chevron-left"></i></a>
            </div>
        </header>

        <div class="inner-wrapper">
            <section role="main" class="content-body">
                <?php include("global/content.php"); ?>
            </section>
        </div>

        <aside id="sidebar-right" class="sidebar-right">
            <?php include("global/kanan.php"); ?>
        </aside>
    </section>

    <!-- NOTIF MODAL -->
    <?php
    $m = htmlspecialchars($_REQUEST['m'] ?? '');
    $teks = '';
    if ($m) {
        $messages = [
            'addok'     => 'Penambahan Data Berhasil',
            'addgagal'  => 'Penambahan Data Gagal',
            'delok'     => 'Penghapusan Data Berhasil',
            'delgagal'  => 'Penghapusan Data Gagal',
            'updok'     => 'Update Data Berhasil',
            'updgagal'  => 'Update Data Gagal',
        ];
        $teks = $messages[$m] ?? '';
    }
    ?>
    <a class="mfp-hide" href="#pesan" id="notif_trigger"></a>
    <div id="pesan" class="modal-block modal-header-color modal-block-primary mfp-hide">
        <article class="card">
            <header class="card-header">
                <h2 class="card-title">Informasi</h2>
            </header>
            <div class="card-body">
                <div class="modal-wrapper">
                    <div class="modal-icon"><i class="fas fa-info-circle"></i></div>
                    <div class="modal-text"><h4><?= $teks ?></h4></div>
                </div>
            </div>
            <footer class="card-footer text-right">
                <button class="btn btn-primary modal-dismiss">OK</button>
            </footer>
        </article>
    </div>

    <!-- Vendor Scripts -->
    <script src="vendor/jquery/jquery.js"></script>
    <script src="vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
    <script src="vendor/popper/umd/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.js"></script>
    <script src="vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script src="vendor/common/common.js"></script>
    <script src="vendor/nanoscroller/nanoscroller.js"></script>
    <script src="vendor/magnific-popup/jquery.magnific-popup.js"></script>
    <script src="vendor/jquery-placeholder/jquery.placeholder.js"></script>
    <script src="vendor/jquery-ui/jquery-ui.js"></script>
    <script src="vendor/select2/js/select2.js"></script>
    <script src="vendor/pnotify/pnotify.custom.js"></script>
    <script src="vendor/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/media/js/dataTables.bootstrap4.min.js"></script>
    <script src="vendor/datatables/extras/TableTools/Buttons-1.4.2/js/dataTables.buttons.min.js"></script>
    <script src="vendor/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.bootstrap4.min.js"></script>
    <script src="vendor/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.html5.min.js"></script>
    <script src="vendor/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.print.min.js"></script>
    <script src="vendor/datatables/extras/TableTools/JSZip-2.5.0/jszip.min.js"></script>
    <script src="vendor/datatables/extras/TableTools/pdfmake-0.1.32/pdfmake.min.js"></script>
    <script src="vendor/datatables/extras/TableTools/pdfmake-0.1.32/vfs_fonts.js"></script>
    <script src="vendor/isotope/isotope.js"></script>

    <!-- Theme -->
    <script src="js/theme.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/theme.init.js"></script>
    <script src="js/examples/examples.header.menu.js"></script>
    <script src="js/examples/examples.modals.js"></script>
    <script src="js/examples/examples.mediagallery.js"></script>
    <script src="js/examples/examples.datatables.default.js"></script>
    <script src="js/examples/examples.datatables.row.with.details.js"></script>
    <script src="js/examples/examples.datatables.tabletools.js"></script>

    <?php if ($m): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('notif_trigger').click();
    });
    </script>
    <?php endif; ?>
</body>
</html>
<?php
} else {
    header("location: index.php");
    exit;
}
?>