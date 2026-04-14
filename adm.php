<?php
session_start();
if($_SESSION["statep"]=="login" and $_SESSION["levelp"]=="opr")
{ header("location:opr.php"); } 

if($_SESSION["statep"]=="login" and ($_SESSION["levelp"]=="adm" or $_SESSION["levelp"]=="su"))
{ 
include("global/koneksi.php");
$stmt_sek = $bp->prepare("SELECT bagian from bagian where id_seksi = :seksi");
$stmt_sek->execute(['seksi' => $_SESSION['seksip']]);
$sek = $stmt_sek->fetch();

$stmt_set = $bp->query("SELECT * FROM setting LIMIT 1");
$set = $stmt_set->fetch();
?>
<!doctype html>
<html class="fixed header-dark">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title><?php echo"$set[title_head]";?></title>
		<meta name="keywords" content="<?php echo"$set[nama_kantor]";?>" />
		<meta name="description" content="<?php echo"$set[nama_kantor]";?>">
		<meta name="theme-color" content="#0a0e1a">
		<meta name="<?php echo"$set[nama_kantor]";?>" content="<?php echo"$set[nama_kantor]";?>">

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
		<link rel="stylesheet" href="vendor/jquery-ui/jquery-ui.css" />
		<link rel="stylesheet" href="vendor/jquery-ui/jquery-ui.theme.css" />
		<link rel="stylesheet" href="vendor/bootstrap-multiselect/css/bootstrap-multiselect.css" />
		<link rel="stylesheet" href="vendor/morris/morris.css" />
        <link rel="stylesheet" href="vendor/simple-line-icons/css/simple-line-icons.css">
		<link rel="stylesheet" href="vendor/elusive-icons/css/elusive-icons.css">

		<!-- Specific Page Vendor CSS -->
		<link rel="stylesheet" href="vendor/select2/css/select2.css" />
		<link rel="stylesheet" href="vendor/select2-bootstrap-theme/select2-bootstrap.min.css" />
		<link rel="stylesheet" href="vendor/datatables/media/css/dataTables.bootstrap4.css" />

		<!-- Specific Page Vendor CSS modal-->
		<link rel="stylesheet" href="vendor/pnotify/pnotify.custom.css" />

		<!-- Specific Page Vendor CSS (FORM STOK ADD)-->
		<link rel="stylesheet" href="vendor/bootstrap-fileupload/bootstrap-fileupload.min.css" />
		


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
		<!-- Background Decorative Elements -->
		<div class="blob blob-1"></div>
		<div class="blob blob-2"></div>
		<div class="blob blob-3"></div>

		<section class="body">

			<!-- start: header -->
			<header class="header">
				<div class="logo-container">
					<a href="index.php" class="logo">
						<img src="img/<?php echo"$set[logo_header]";?>" width="75" height="35" alt="<?php echo"$set[logo_header]";?>" />
					</a>
					<form action="?p=stok" method="get" class="logo">
						<input type="hidden" name="p" value="cari">
						<div class="input-group mb-3">
						<input type="text" class="form-control" name="k" id="k" placeholder="Cari Barang...">
						<span class="input-group-append">
							<button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Cari</button>
						</span>
						</div>
					</form>
					
					<div class="d-md-none toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
						<i class="fas fa-bars" aria-label="Toggle sidebar"></i>
					</div>
				</div>
			
				<!-- start: search & user box -->
				<div class="header-right">					
					<span class="separator"></span>
					<ul class="notifications">
<!--ANGGARAN START-->
<?php
$stmt_ag = $bp->query("SELECT * FROM anggaran WHERE status = '1'");
$ag_items = $stmt_ag->fetchAll();
$jag = count($ag_items);
$notif_ag = ($jag > 0) ? "<span class='badge'>$jag</span>" : "";
?>
						<li>
							<a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
								<i class="fas fa-money-bill"></i>
								<?php echo"$notif_ag";?>
							</a>
			
							<div class="dropdown-menu notification-menu">
								<div class="notification-title">
									<span class="float-right badge badge-default"><?php echo"$jag";?></span>
									Anggaran
								</div>
			
								<div class="content">
								<ul>
									<li>
<?php
if($jag>0)
{
    $jpagu=0;
    $jserapan=0;
    $ta = "";
    foreach($ag_items as $ag)
    {
        $sisa=$ag["pagu_anggaran"]-$ag["serapan_anggaran"];
        $pagu=number_format($ag["pagu_anggaran"]);
        $serapan=number_format($ag["serapan_anggaran"]);
        $sisa_fmt=number_format($sisa);
        $persen=($ag["pagu_anggaran"] > 0) ? ($ag["serapan_anggaran"]/$ag["pagu_anggaran"]*100) : 0;
        $persen_fmt=number_format($persen,2);

        echo"
        <p class='clearfix mb-1'>
            <a href='?p=anggaran_det&id=$ag[id_anggaran]' class='btn btn-xs message float-right text-dark'><span class='highlight'> $persen_fmt %</span> <b>$serapan</b>  
            dari <b>$pagu</b></a>
        </p>
        ";
        $jpagu=$jpagu+$ag["pagu_anggaran"];
        $jserapan=$jserapan+$ag["serapan_anggaran"];
        $ta=$ag["tahun_anggaran"];
    }
    $jpersen=($jpagu > 0) ? ($jserapan/$jpagu*100) : 0;
    $jpersen_fmt=number_format($jpersen,2);
    $jpagu_fmt=number_format($jpagu);
    $jserapan_fmt=number_format($jserapan);
    echo"<hr>
        <p class='clearfix mb-1'> 
            <a href='?p=anggaran_ta&ta=$ta' class='btn btn-xs message float-right text-dark'>Total : <span class='highlight'>$jpersen_fmt %</span> <b>$jserapan_fmt</b>  
            dari <b>$jpagu_fmt</b></a>
        </p>
    </li>";
}
else { echo"<li>Tidak Ada Anggaran Aktif</li>";}
?>
								</ul>
			
									<hr />
			
									<div class="text-right">
										<a href="?p=anggaran" class="view-more"><i class='fa fa-check'></i> Tampilkan Semua <i class='fa fa-arrow-right'></i></a>
									</div>
								</div>
							</div>
						</li>
<!--ANGGARAN END-->
<!--ADD STOK START-->
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
								<?php echo"$notif_in";?>
							</a>
			
							<div class="dropdown-menu notification-menu">
								<div class="notification-title">
									<span class="float-right badge badge-default"><?php echo"$jin";?></span>
									Penambahan Stok
								</div>
			
								<div class="content">
								<ul>
<?php
foreach($in_items as $in)
{
	echo"
										<li>
											<p class='clearfix mb-1'>
												<a href='?p=order' class='btn btn-xs message float-left text-dark'>".htmlspecialchars($in['nama_barang'])."</a>
												<span class='message float-right text-dark'>$in[jml_in] $in[satuan]</span>
											</p>
										</li>
										";
} ?>
									</ul>
			
									<hr />
			
									<div class="text-right">
										<a href="?p=cekin" class="view-more"><i class='fa fa-check'></i> Tampilkan Semua <i class='fa fa-arrow-right'></i></a>
									</div>
								</div>
							</div>
						</li>
<!--ADD STOK END-->
<!--DAFTAR PERMINTAAN START-->
						<?php
$stmt_ptg = $bp->query("SELECT DISTINCT kode_nota, petugas FROM stok_inout WHERE status = '1'");
$ptg_items = $stmt_ptg->fetchAll();
$jpt = count($ptg_items);

$stmt_psn = $bp->query("SELECT DISTINCT petugas FROM pesanan WHERE jumlah_stok = '0'");
$psn_items = $stmt_psn->fetchAll();
$jps = count($psn_items);

$jnotif = $jpt + $jps;
$notif_pt = ($jnotif > 0) ? "<span class='badge'>$jnotif</span>" : "";
?>
						<li>
							<a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
								<i class="fas fa-bell"></i>
								<?php echo"$notif_pt";?>
							</a>
			
							<div class="dropdown-menu notification-menu">
								<div class="notification-title">
									<span class="float-right badge badge-default"><?php echo"$jpt";?></span>
									<a href="?p=permintaan" class="btn btn-xs btn-primary text-left">Permintaan</a>
								</div>
			
								<div class="content">
								<ul>
<?php
foreach($ptg_items as $pt)
{
    $stmt_p = $bp->prepare("SELECT nama FROM petugas WHERE id_petugas = :petugas");
    $stmt_p->execute(['petugas' => $pt['petugas']]);
    $p = $stmt_p->fetch();

    $stmt_jo = $bp->prepare("SELECT id_inout FROM stok_inout WHERE jenis = 'out' AND status = '1' AND petugas = :petugas AND kode_nota = :nota");
    $stmt_jo->execute(['petugas' => $pt['petugas'], 'nota' => $pt['kode_nota']]);
    $jo = $stmt_jo->rowCount();

	echo"
										<li>
											<p class='clearfix mb-1'>
												<a href='?p=permintaan&kode=".htmlspecialchars($pt['kode_nota'])."' class='btn btn-xs message float-left text-dark'>".htmlspecialchars($p['nama'] ?? 'User')."</a>
												<span class='message float-right text-dark'>$jo item</span>
											</p>
										</li>
										";
} ?>
								</ul>
								</div>
								<hr />

								<!---->
								<div class="notification-title">
									<span class="float-right badge badge-default"><?php echo"$jps";?></span>
									<a href="?p=pesanan" class="btn btn-xs btn-primary text-left">Pesanan</a>
								</div>
			
								<div class="content">
								<ul>
<?php
foreach($psn_items as $ps)
{
    $stmt_p = $bp->prepare("SELECT nama FROM petugas WHERE id_petugas = :petugas");
    $stmt_p->execute(['petugas' => $ps['petugas']]);
    $p = $stmt_p->fetch();

    $stmt_jo = $bp->prepare("SELECT id_pesanan FROM pesanan WHERE petugas = :petugas");
    $stmt_jo->execute(['petugas' => $ps['petugas']]);
    $jo = $stmt_jo->rowCount();

	echo"
										<li>
											<p class='clearfix mb-1'>
												<a href='?p=pesanan&pt=".htmlspecialchars($ps['petugas'])."' class='btn btn-xs message float-left text-dark'>".htmlspecialchars($p['nama'] ?? 'User')."</a>
												<span class='message float-right text-dark'>$jo item</span>
											</p>
										</li>
										";
} ?>
								</ul>
							</div>

							
							</div>
						</li>
<!--DAFTAR PERMINTAAN END-->
<!--CART START-->
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
								<?php echo"$notif_cart";?>
							</a>
			
							<div class="dropdown-menu notification-menu">
								<div class="notification-title">
									<span class="float-right badge badge-default"><?php echo"$jre";?></span>
									Permintaan
								</div>
			
								<div class="content">
								<ul>
<?php
foreach($re_items as $re)
{
	echo"
										<li>
											<p class='clearfix mb-1'>
												<a href='?p=order' class='btn btn-xs message float-left text-dark'>".htmlspecialchars($re['nama_barang'])."</a>
												<span class='message float-right text-dark'>$re[jml_req] $re[satuan]</span>
											</p>
										</li>
										";
} ?>
									</ul>
			
									<hr />
			
									<div class="text-right">
										<a href="?p=cekout" class="view-more"> Ajukan <i class='fa fa-arrow-right'></i></a>
									</div>
								</div>
							</div>
						</li>
<!--CART EDN-->
					</ul>
			<span class="separator"></span>
					<div id="userbox" class="userbox">
						<a href="#" data-toggle="dropdown">
							<figure class="profile-picture">
								<img src="img/users/<?php echo"$_SESSION[photop]";?>" alt="<?php echo"$_SESSION[namap]";?>" class="rounded-circle" data-lock-picture="img/users/<?php echo"$_SESSION[photop]";?>" />
							</figure>
							<div class="profile-info">
								<span class="name"><?php echo htmlspecialchars($_SESSION['namap']);?></span>
								<span class="role"><?php echo htmlspecialchars($sek['bagian'] ?? '');?></span>
							</div>
			
							<i class="fa custom-caret"></i>
						</a>
			
						<div class="dropdown-menu">
							<ul class="list-unstyled">
								<li class="divider"></li>
								<li>
									<a role="menuitem" tabindex="-1" href="?p=profil"><i class="fas fa-user"></i> Akun Saya</a>
								</li>
								<li>
									<a role="menuitem" tabindex="-1" href="?p=setting"><i class="fas fa-cog"></i> Pengaturan Aplikasi</a>
								</li>
								<li>
									<a role="menuitem" tabindex="-1" href="?p=anggaran"><i class="fas fa-money-bill"></i> Anggaran</a>
								</li>
								<li>
									<a role="menuitem" tabindex="-1" href="?p=logout"><i class="fas fa-power-off"></i> Logout</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!-- end: search & user box -->
			</header>
			<!-- end: header -->

			<div class="inner-wrapper">
				<!-- start: sidebar -->
				<?php include("global/leftnav-su.php");
				?>
				<!-- end: sidebar -->

				<section role="main" class="content-body">
					<header class="page-header">
						<h2><?php include("global/header.php");?>	</h2>
					
						<div class="right-wrapper text-right">					
							<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fas fa-chevron-left"></i></a>
						</div>
					</header>

					<!-- start: page -->
					<?php
					include("global/content.php");?>
					<!-- end: page -->
</section>
</div>

<?php
include("global/kanan.php");
?>
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

<!-- Specific Page Vendor -->
<script src="vendor/select2/js/select2.js"></script>
<script src="vendor/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/media/js/dataTables.bootstrap4.min.js"></script>
<script src="vendor/datatables/extras/TableTools/Buttons-1.4.2/js/dataTables.buttons.min.js"></script>
<script src="vendor/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.bootstrap4.min.js"></script>
<script src="vendor/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.html5.min.js"></script>
<script src="vendor/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.print.min.js"></script>
<script src="vendor/datatables/extras/TableTools/JSZip-2.5.0/jszip.min.js"></script>
<script src="vendor/datatables/extras/TableTools/pdfmake-0.1.32/pdfmake.min.js"></script>
<script src="vendor/datatables/extras/TableTools/pdfmake-0.1.32/vfs_fonts.js"></script>

<!-- Specific Page Vendor -->
<script src="vendor/select2/js/select2.js"></script>
<script src="vendor/pnotify/pnotify.custom.js"></script>

<!-- Specific Page Vendor -->
<script src="vendor/autosize/autosize.js"></script>
<script src="vendor/bootstrap-fileupload/bootstrap-fileupload.min.js"></script>

<!-- Theme Base, Components and Settings -->
<script src="js/theme.js"></script>

<!-- Theme Custom -->
<script src="js/custom.js"></script>

<!-- Theme Initialization Files -->
<script src="js/theme.init.js"></script>

<!-- Examples -->
<script src="js/examples/examples.datatables.default.js"></script>
<script src="js/examples/examples.datatables.row.with.details.js"></script>
<script src="js/examples/examples.datatables.tabletools.js"></script>

<!-- Examples -->
<script src="js/examples/examples.modals.js"></script>
		</body>
</html>
<?php
}
else
{ header("location:	index.php"); } 
?>