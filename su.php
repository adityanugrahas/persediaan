<?php
session_start();

// Access control
if (empty($_SESSION["statep"]) || $_SESSION["statep"] !== "login") {
    header("location:index.php");
    exit;
}

$level = $_SESSION["levelp"];
if ($level === "adm") {
    header("location:adm.php");
    exit;
}
if ($level === "opr") {
    header("location:opr.php");
    exit;
}

if ($level === "su") {
    require_once "global/koneksi.php";

    // Fetch settings
    $stmt_set = $bp->query("SELECT * FROM setting ORDER BY setting_id LIMIT 1");
    $set = $stmt_set->fetch();

    // Fetch user department
    $stmt_sek = $bp->prepare("SELECT bagian FROM bagian WHERE id_seksi = :seksi");
    $stmt_sek->execute(['seksi' => $_SESSION['seksip']]);
    $sek = $stmt_sek->fetch();
?>
<!DOCTYPE html>
<html lang="id" class="fixed has-top-menu">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($set['title_head']) ?> - Super User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- Modern Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="vendor/animate/animate.css">
    <link rel="stylesheet" href="vendor/font-awesome/css/all.min.css" />
    <link rel="stylesheet" href="vendor/magnific-popup/magnific-popup.css" />
    <link rel="stylesheet" href="vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />
    <link rel="stylesheet" href="vendor/jquery-ui/jquery-ui.css" />
    <link rel="stylesheet" href="vendor/jquery-ui/jquery-ui.theme.css" />
    <link rel="stylesheet" href="vendor/select2/css/select2.css" />
    <link rel="stylesheet" href="vendor/select2-bootstrap-theme/select2-bootstrap.min.css" />
    <link rel="stylesheet" href="vendor/datatables/media/css/dataTables.bootstrap4.css" />
    <link rel="stylesheet" href="vendor/pnotify/pnotify.custom.css" />

    <!-- Theme CSS -->
    <link rel="stylesheet" href="css/theme.css" />
    <link rel="stylesheet" href="css/skins/default.css" />
    <link rel="stylesheet" href="css/custom.css">

    <script src="vendor/modernizr/modernizr.js"></script>
</head>
<body onload="myFunction()" class="loading-overlay-showing" data-loading-overlay>
    <!-- Background Decorative Blobs -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <section class="body">
        <!-- start: header -->
        <header class="header">
            <div class="logo-container">
                <a href="su.php" class="logo">
                    <img src="img/<?= htmlspecialchars($set['logo_header']) ?>" width="100" height="45" alt="Logo" style="object-fit: contain;" />
                </a>
                
                <div class="d-md-none toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
                    <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
                </div>
            </div>
            
            <div class="header-right">
                <form action="?p=stok" method="get" class="search nav-form d-none d-md-block mr-3">
                    <input type="hidden" name="p" value="cari">
                    <div class="input-group">
                        <input type="text" class="form-control" name="k" id="k" placeholder="Cari Barang...">
                        <span class="input-group-append">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                        </span>
                    </div>
                </form>

                <span class="separator"></span>
                <ul class="notifications">
                    <!-- ANGGARAN NOTIF -->
                    <?php
                    $stmt_ag = $bp->query("SELECT * FROM anggaran WHERE status = 1");
                    $ag_items = $stmt_ag->fetchAll();
                    $jag = count($ag_items);
                    $notif_ag = ($jag > 0) ? "<span class='badge'>$jag</span>" : "";
                    ?>
                    <li>
                        <a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
                            <i class="fas fa-money-bill"></i>
                            <?= $notif_ag ?>
                        </a>
                        <div class="dropdown-menu notification-menu">
                            <div class="notification-title">
                                <span class="float-right badge badge-default"><?= $jag ?></span>
                                Anggaran
                            </div>
                            <div class="content">
                                <ul>
                                    <?php if ($jag > 0): 
                                        $jpagu = 0; $jserapan = 0; $ta = "";
                                        foreach ($ag_items as $ag): 
                                            $sisa = $ag["pagu_anggaran"] - $ag["serapan_anggaran"];
                                            $pagu = number_format($ag["pagu_anggaran"]);
                                            $serapan = number_format($ag["serapan_anggaran"]);
                                            $persen = ($ag["pagu_anggaran"] > 0) ? ($ag["serapan_anggaran"] / $ag["pagu_anggaran"] * 100) : 0;
                                            $persen_fmt = number_format($persen, 2);
                                            $jpagu += $ag["pagu_anggaran"];
                                            $jserapan += $ag["serapan_anggaran"];
                                            $ta = $ag["tahun_anggaran"];
                                    ?>
                                        <li>
                                            <p class='clearfix mb-1'>
                                                <a href='?p=anggaran_det&id=<?= $ag["id_anggaran"] ?>' class='btn btn-xs message float-right text-dark'>
                                                    <span class='highlight'><?= $persen_fmt ?>%</span> <b><?= $serapan ?></b> dari <b><?= $pagu ?></b>
                                                </a>
                                            </p>
                                        </li>
                                    <?php endforeach; 
                                        $jpersen = ($jpagu > 0) ? ($jserapan / $jpagu * 100) : 0;
                                        $jpersen_fmt = number_format($jpersen, 2);
                                    ?>
                                        <hr>
                                        <li>
                                            <p class='clearfix mb-1'>
                                                <a href='?p=anggaran_ta&ta=<?= $ta ?>' class='btn btn-xs message float-right text-dark'>
                                                    Total: <span class='highlight'><?= $jpersen_fmt ?>%</span> <b><?= number_format($jserapan) ?></b> dari <b><?= number_format($jpagu) ?></b>
                                                </a>
                                            </p>
                                        </li>
                                    <?php else: ?>
                                        <li>Tidak Ada Anggaran Aktif</li>
                                    <?php endif; ?>
                                </ul>
                                <hr />
                                <div class="text-right">
                                    <a href="?p=anggaran" class="view-more">Tampilkan Semua <i class='fa fa-arrow-right'></i></a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- STOK IN NOTIF -->
                    <?php
                    $stmt_in = $bp->query("SELECT i.id_inout, i.id_barang, b.nama_barang, i.jml_in, i.satuan 
                                          FROM stok_inout i 
                                          JOIN stok_barang b ON i.id_barang = b.id_barang 
                                          WHERE i.jenis = 'in' AND i.status = '0'");
                    $in_items = $stmt_in->fetchAll();
                    $jin = count($in_items);
                    $notif_in = ($jin > 0) ? "<span class='badge'>$jin</span>" : "";
                    ?>
                    <li>
                        <a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
                            <i class="fas fa-download"></i>
                            <?= $notif_in ?>
                        </a>
                        <div class="dropdown-menu notification-menu">
                            <div class="notification-title">
                                <span class="float-right badge badge-default"><?= $jin ?></span>
                                Penambahan Stok
                            </div>
                            <div class="content">
                                <ul>
                                    <?php foreach ($in_items as $in): ?>
                                        <li>
                                            <p class='clearfix mb-1'>
                                                <a href='?p=cekin' class='btn btn-xs message float-left text-dark'><?= htmlspecialchars($in['nama_barang']) ?></a>
                                                <span class='message float-right text-dark'><?= $in['jml_in'] ?> <?= $in['satuan'] ?></span>
                                            </p>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <hr />
                                <div class="text-right">
                                    <a href="?p=cekin" class="view-more">Tampilkan Semua <i class='fa fa-arrow-right'></i></a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- PERMINTAAN & PESANAN NOTIF -->
                    <?php
                    $stmt_ptg = $bp->query("SELECT DISTINCT kode_nota, petugas FROM stok_inout WHERE status = '1'");
                    $ptg_items = $stmt_ptg->fetchAll();
                    $jpt = count($ptg_items);

                    $stmt_psn = $bp->query("SELECT DISTINCT petugas FROM pesanan WHERE jumlah_stok = 0");
                    $psn_items = $stmt_psn->fetchAll();
                    $jps = count($psn_items);
                    
                    $jnotif = $jpt + $jps;
                    $notif_all = ($jnotif > 0) ? "<span class='badge'>$jnotif</span>" : "";
                    ?>
                    <li>
                        <a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <?= $notif_all ?>
                        </a>
                        <div class="dropdown-menu notification-menu">
                            <div class="notification-title"><span class="float-right badge badge-default"><?= $jpt ?></span> Permintaan</div>
                            <div class="content">
                                <ul>
                                    <?php foreach ($ptg_items as $pt): 
                                        $stmt_p = $bp->prepare("SELECT nama FROM petugas WHERE id_petugas = :id");
                                        $stmt_p->execute(['id' => $pt['petugas']]);
                                        $pname = $stmt_p->fetch();
                                        
                                        $stmt_jo = $bp->prepare("SELECT id_inout FROM stok_inout WHERE jenis='out' AND status='1' AND petugas=:petugas AND kode_nota=:nota");
                                        $stmt_jo->execute(['petugas' => $pt['petugas'], 'nota' => $pt['kode_nota']]);
                                        $jo_count = $stmt_jo->rowCount();
                                    ?>
                                        <li>
                                            <p class='clearfix mb-1'>
                                                <a href='?p=permintaan&kode=<?= $pt['kode_nota'] ?>' class='btn btn-xs message float-left text-dark'><?= htmlspecialchars($pname['nama'] ?? 'User') ?></a>
                                                <span class='message float-right text-dark'><?= $jo_count ?> item</span>
                                            </p>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <hr />
                            <div class="notification-title"><span class="float-right badge badge-default"><?= $jps ?></span> Pesanan</div>
                            <div class="content">
                                <ul>
                                    <?php foreach ($psn_items as $ps): 
                                        $stmt_p = $bp->prepare("SELECT nama FROM petugas WHERE id_petugas = :id");
                                        $stmt_p->execute(['id' => $ps['petugas']]);
                                        $pname = $stmt_p->fetch();
                                        
                                        $stmt_jo = $bp->prepare("SELECT id_pesanan FROM pesanan WHERE petugas = :id");
                                        $stmt_jo->execute(['id' => $ps['petugas']]);
                                        $jo_count = $stmt_jo->rowCount();
                                    ?>
                                        <li>
                                            <p class='clearfix mb-1'>
                                                <a href='?p=pesanan&pt=<?= $ps['petugas'] ?>' class='btn btn-xs message float-left text-dark'><?= htmlspecialchars($pname['nama'] ?? 'User') ?></a>
                                                <span class='message float-right text-dark'><?= $jo_count ?> item</span>
                                            </p>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </li>

                    <!-- CART NOTIF -->
                    <?php
                    $stmt_re = $bp->prepare("SELECT i.id_barang, i.satuan, i.jml_req, b.nama_barang 
                                            FROM stok_inout i 
                                            JOIN stok_barang b ON i.id_barang = b.id_barang 
                                            WHERE i.jenis = 'out' AND i.status = '0' AND i.petugas = :petugas");
                    $stmt_re->execute(['petugas' => $_SESSION['idp']]);
                    $re_items = $stmt_re->fetchAll();
                    $jre = count($re_items);
                    $notif_cart = ($jre > 0) ? "<span class='badge'>$jre</span>" : "";
                    ?>
                    <li>
                        <a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
                            <i class="fas fa-shopping-cart"></i>
                            <?= $notif_cart ?>
                        </a>
                        <div class="dropdown-menu notification-menu">
                            <div class="notification-title"><span class="float-right badge badge-default"><?= $jre ?></span> Permintaan Saya</div>
                            <div class="content">
                                <ul>
                                    <?php foreach ($re_items as $re): ?>
                                        <li>
                                            <p class='clearfix mb-1'>
                                                <a href='?p=cekout' class='btn btn-xs message float-left text-dark'><?= htmlspecialchars($re['nama_barang']) ?></a>
                                                <span class='message float-right text-dark'><?= $re['jml_req'] ?> <?= $re['satuan'] ?></span>
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
                <div id="userbox" class="userbox">
                    <a href="#" data-toggle="dropdown">
                        <figure class="profile-picture">
                            <img src="img/users/<?= htmlspecialchars($_SESSION['photop']) ?>" alt="User" class="rounded-circle" />
                        </figure>
                        <div class="profile-info">
                            <span class="name"><?= htmlspecialchars($_SESSION['namap']) ?></span>
                            <span class="role"><?= htmlspecialchars($sek['bagian'] ?? 'General') ?></span>
                        </div>
                        <i class="fa custom-caret"></i>
                    </a>
                    <div class="dropdown-menu">
                        <ul class="list-unstyled">
                            <li class="divider"></li>
                            <li><a href="?p=profil"><i class="fas fa-user"></i> Akun Saya</a></li>
                            <li><a href="?p=setting"><i class="fas fa-cog"></i> Pengaturan Aplikasi</a></li>
                            <li><a href="?p=anggaran"><i class="fas fa-money-bill"></i> Anggaran</a></li>
                            <li><a href="proses/logout.php"><i class="fas fa-power-off"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <section class="page-header">
            <h1 class="h2"><?php include("global/header.php"); ?></h1>
            <div class="right-wrapper text-right">
                <ol class="breadcrumbs">
                    <li><a href="su.php"><i class="fas fa-home"></i></a></li>
                    <li><span>Dashboard</span></li>
                </ol>
                <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fas fa-chevron-left"></i></a>
            </div>
        </section>

        <div class="inner-wrapper">
            <main role="main" class="content-body">
                <?php include("global/content.php"); ?>
            </main>
        </div>

        <aside id="sidebar-right" class="sidebar-right">
            <?php include("global/kanan.php"); ?>
        </aside>
    </section>

    <!-- NOTIF MODAL -->
    <?php
    $m = htmlspecialchars($_GET['m'] ?? '');
    $teks = "";
    if ($m) {
        $messages = [
            'addok' => "Penambahan Data Berhasil",
            'addgagal' => "Penambahan Data Gagal",
            'delok' => "Penghapusan Data Berhasil",
            'delgagal' => "Penghapusan Data Gagal",
            'updok' => "Update Data Berhasil",
            'updgagal' => "Update Data Gagal"
        ];
        $teks = $messages[$m] ?? "";
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
    <script src="vendor/popper/umd/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.js"></script>
    <script src="vendor/nanoscroller/nanoscroller.js"></script>
    <script src="vendor/magnific-popup/jquery.magnific-popup.js"></script>
    <script src="vendor/jquery-ui/jquery-ui.js"></script>
    <script src="vendor/select2/js/select2.js"></script>
    <script src="vendor/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/media/js/dataTables.bootstrap4.min.js"></script>
    <script src="vendor/pnotify/pnotify.custom.js"></script>

    <!-- Theme Initializer -->
    <script src="js/theme.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/theme.init.js"></script>

    <script>
    function myFunction() {
        <?php if($m): ?>
        document.getElementById("notif_trigger").click();
        <?php endif; ?>
    }
    </script>
</body>
</html>
<?php 
} else { 
    header("location:index.php"); 
    exit;
} 
?>
