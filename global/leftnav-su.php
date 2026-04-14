<?php
$current_p = $_GET['p'] ?? '';
?>
<aside id="sidebar-left" class="sidebar-left">
				
    <div class="sidebar-header">
        <div class="sidebar-title">
            Main Console
        </div>
        <div class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>
				
    <div class="nano">
            <div class="nano-content">
                <nav id="menu" class="nav-main" role="navigation">
                
                    <ul class="nav nav-main">
                        <li class="<?= ($current_p == '' || $current_p == 'home') ? 'nav-active' : '' ?>">
                            <a class="nav-link" href="su.php">
                                <i class="fas fa-th-large"></i>
                                <span>DASHBOARD</span>
                            </a>                        
                        </li>
                        <li class="nav-parent <?= in_array($current_p, ['stok_all', 'stok_kat', 'kat', 'kat_add', 'rekap', 'rekap_stok', 'rekap_pemakai', 'stok', 'stok_add']) ? 'nav-expanded nav-active' : '' ?>">
                            <a class="nav-link" href="#">
                                <i class="fas fa-boxes"></i>
                                <span>PERSEDIAAN</span>
                            </a>
                            <ul class="nav nav-children">
                                <li class="<?= $current_p == 'stok_all' ? 'nav-active' : '' ?>">
                                    <a class="nav-link" href="?p=stok_all"> 
                                        DATA BARANG
                                    </a>
                                </li>
                                <li class="nav-parent <?= in_array($current_p, ['stok_kat', 'kat', 'kat_add']) ? 'nav-expanded' : '' ?>">
                                    <a>
                                        KATEGORI
                                    </a>
                                    <ul class="nav nav-children">
                                <?php
                                    $skat = db_fetch_all($bp, "SELECT * FROM kategori ORDER BY nama_kat");
                                    if (count($skat) > 0) {
                                        echo "<li class='".($current_p == 'stok_all' ? 'active' : '')."'><a class='nav-link' href='?p=stok_all'><i class='fas fa-th-large mr-2'></i> Semua Unit</a></li>";
                                        foreach ($skat as $kat) {
                                            $active_kat = ($current_p == 'stok_kat' && ($_GET['kat'] ?? '') == $kat['id_kat']) ? 'active' : '';
                                            echo "<li class='$active_kat'><a class='nav-link' href='?p=stok_kat&kat=" . htmlspecialchars($kat['id_kat']) . "&kat_nm=" . htmlspecialchars($kat['nama_kat']) . "'>" . htmlspecialchars($kat['nama_kat']) . "</a></li>";
                                        }
                                    }
                                ?>
                                        <li class="<?= $current_p == 'kat_add' ? 'active' : '' ?>">
                                            <a class="nav-link" href="?p=kat_add">
                                                <i class="fas fa-plus-circle"></i> Tambah Kategori
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                               
                                <li class="nav-parent <?= in_array($current_p, ['permintaan', 'rekap', 'rekap_stok', 'rekap_pemakai']) ? 'nav-expanded' : '' ?>">
                                    <a class="nav-link" href="#">
                                        <i class="fas fa-truck-loading"></i>
                                        <span>DISTRIBUSI</span>
                                    </a>
                                    <ul class="nav nav-children">
                                        <li class="<?= $current_p == 'permintaan' ? 'active' : '' ?>">
                                            <a class="nav-link" href="?p=permintaan">
                                                 Permintaan   
                                            </a>
                                        </li>
                                        <li class="<?= $current_p == 'rekap' ? 'active' : '' ?>">
                                            <a class="nav-link" href="?p=rekap&f=semua">
                                                Mutasi Barang
                                            </a>
                                        </li>
                                        <li class="<?= $current_p == 'rekap_stok' ? 'active' : '' ?>">
                                            <a class="nav-link" href="?p=rekap_stok">
                                                Rekap Stok
                                            </a>
                                        </li>
                                        <li class="<?= $current_p == 'rekap_pemakai' ? 'active' : '' ?>">
                                            <a class="nav-link" href="?p=rekap_pemakai">
                                                Rekap Pemakai
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="<?= $current_p == 'stok' ? 'active' : '' ?>">
                                    <a class="nav-link" href="?p=stok">
                                        REKOM PEMBELIAN
                                    </a>
                                </li>
                                <li class="<?= $current_p == 'stok_add' ? 'active' : '' ?>">
                                    <a class="nav-link" href="?p=stok_add">
                                    <i class="fa fa-plus"></i> Tambah Barang
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="<?= $current_p == 'permintaan' ? 'nav-active' : '' ?>">
                            <a class="nav-link" href="?p=permintaan">
                                <i class="fas fa-paper-plane" aria-hidden="true"></i>
                                <span>PERMINTAAN
                                <?php
                                if (isset($jpt) && $jpt > 0) {
                                    echo "<span class='float-right badge badge-danger'>{$jpt}</span>";
                                }
                                ?></span>
                            </a>                        
                        </li>
                        <li class="<?= $current_p == 'pesanan' ? 'nav-active' : '' ?>">
                            <a class="nav-link" href="?p=pesanan">
                                <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                                <span>PESANAN
                                <?php
                                if (isset($jps) && $jps > 0) {
                                    echo "<span class='float-right badge badge-danger'>{$jps}</span>";
                                }
                                ?>
                                </span>
                            </a>                        
                        </li>
<?php
$hampir = (int)db_fetch_column($bp, "SELECT COUNT(*) FROM stok_barang WHERE jumlah_stok <= stok_minimal AND aktif = 'ya'");
?>
                        <li class="<?= $current_p == 'menipis' ? 'nav-active' : '' ?>">
                            <a class="nav-link" href="?p=menipis">
                                <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                                <span>STOK MENIPIS <span class="float-right badge badge-danger"><?= $hampir ?></span></span>
                            </a>                        
                        </li>
                        <li class="<?= $current_p == 'cekin' ? 'nav-active' : '' ?>">
                            <a class="nav-link" href="?p=cekin">
                                <i class="fas fa-plus-circle" aria-hidden="true"></i>
                                <span>PENAMBAHAN STOK
                                <?php
                                if (isset($jin) && $jin > 0) {
                                    echo "<span class='float-right badge badge-danger'>{$jin}</span>";
                                }
                                ?> </span>
                            </a>                        
                        </li>
                        <li class="nav-parent <?= in_array($current_p, ['bmn_kat', 'bmn', 'bmn_dist', 'bmn_add']) ? 'nav-expanded nav-active' : '' ?>">
                            <a class="nav-link" href="#">
                                <i class="fas fa-desktop" aria-hidden="true"></i>
                                <span>ASSET & BMN</span>
                            </a>
                            <ul class="nav nav-children">
                                <li class="<?= $current_p == 'bmn_kat' ? 'active' : '' ?>">
                                    <a class="nav-link" href="?p=bmn_kat">
                                        DATA BMN
                                    </a>
                                </li>
                                <li class="nav-parent <?= $current_p == 'bmn' ? 'nav-expanded' : '' ?>">
                                    <a>
                                        KATEGORI
                                    </a>
                                    <ul class="nav nav-children">
                                    <?php
                                        $skat_bmn = db_fetch_all($bp, "SELECT * FROM kat_bmn ORDER BY nama_kat");
                                        if (count($skat_bmn) > 0) {
                                            echo "<li class='".($current_p == 'bmn_kat' ? 'active' : '')."'><a class='nav-link' href='?p=kat'><i class='fa fa-cube'></i> Semua Kategori</a></li>";
                                            foreach ($skat_bmn as $kat) {
                                                $active_bmn = ($current_p == 'bmn' && ($_GET['kat'] ?? '') == $kat['id_kat']) ? 'active' : '';
                                                echo "<li class='$active_bmn'><a class='nav-link' href='?p=stok&kat=" . htmlspecialchars($kat['id_kat']) . "'><i class='fa fa-cube'></i> " . htmlspecialchars($kat['nama_kat']) . "</a></li>";
                                            }
                                        }
                                    ?>
                                        <li>
                                            <a class="nav-link" href="?p=kat_add">
                                                <i class="fa fa-plus"></i> Tambah Kategori
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                               
                                <li class="<?= $current_p == 'bmn_dist' ? 'active' : '' ?>">
                                    <a class="nav-link" href="?p=bmn_dist">
                                        DISTRIBUSI
                                    </a>
                                </li>
                                <li>
                                    <a class="simple-ajax-modal" href="ajax/bmn_add.php">
                                    <i class="fa fa-plus"></i> Tambah BMN
                                    </a>
                                </li>
                            </ul>
                            
                        </li>
                        
                        <li class="nav-parent <?= in_array($current_p, ['petugas', 'petugas_add', 'bagian', 'kat', 'setting', 'anggaran']) ? 'nav-expanded nav-active' : '' ?>">
                            <a class="nav-link" href="#">
                                <i class="fas fa-sliders-h" aria-hidden="true"></i>
                                <span>SYSTEM CONFIG</span>
                            </a>
                            <ul class="nav nav-children">
                                <li class="nav-parent <?= in_array($current_p, ['petugas', 'petugas_add']) ? 'nav-expanded' : '' ?>">
                                    <a>
                                        MANAJEMEN USER
                                    </a>
                                    <ul class="nav nav-children">
                                    <?php
                                        $sbag = db_fetch_all($bp, "SELECT id_seksi, bagian FROM bagian ORDER BY urutan");
                                        if (count($sbag) > 0) {
                                            echo "<li class='".($current_p == 'petugas' && empty($_GET['bag']) ? 'active' : '')."'><a class='nav-link' href='?p=petugas'>Semua Bagian</a></li>";
                                            foreach ($sbag as $bag) {
                                                $active_bag = ($current_p == 'petugas' && ($_GET['bag'] ?? '') == $bag['id_seksi']) ? 'active' : '';
                                                echo "<li class='$active_bag'><a class='nav-link' href='?p=petugas&bag=" . htmlspecialchars($bag['id_seksi']) . "&bag_nm=" . htmlspecialchars($bag['bagian']) . "'>" . htmlspecialchars($bag['bagian']) . "</a></li>";
                                            }
                                        }
                                    ?>
                                        <li class="<?= $current_p == 'petugas_add' ? 'active' : '' ?>">
                                            <a class="nav-link" href="?p=petugas_add">
                                                <i class="fa fa-plus"></i> Tambah Petugas
                                            </a>
                                        </li>
                                    </ul>
                                </li>                              
                                <li class="<?= $current_p == 'bagian' ? 'active' : '' ?>">
                                    <a class="nav-link" href="?p=bagian">
                                        DATA BAGIAN/SEKSI
                                    </a>
                                </li>
                                <li class="<?= $current_p == 'kat' ? 'active' : '' ?>">
                                    <a class="nav-link" href="?p=kat">
                                        KATEGORI BARANG
                                    </a>
                                </li>
                                <li class="<?= $current_p == 'setting' ? 'active' : '' ?>">
                                    <a class="nav-link" href="?p=setting">
                                        PENGATURAN APP
                                    </a>
                                </li>
                                <li class="<?= $current_p == 'anggaran' ? 'active' : '' ?>">
                                    <a class="nav-link" href="?p=anggaran">
                                        ANGGARAN
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a class="nav-link" href="?p=logout" style="color: var(--danger) !important;">
                                <i class="fas fa-power-off"></i>
                                <span style="font-weight: 700;">EXIT SYSTEM</span>
                            </a>                        
                        </li>
                    </ul>
                </nav>
    
                <hr class="separator" style="opacity: 0.1;" />
 <?php
 $ta = db_fetch($bp, "SELECT tahun_anggaran FROM anggaran WHERE status = 1 LIMIT 1");
 $tha = !empty($ta['tahun_anggaran']) ? "Fiscal Year " . htmlspecialchars($ta['tahun_anggaran']) : "";
 ?>   
                <div class="sidebar-widget widget-stats">
                    <div class="widget-header">
                        <h6><?= $tha ?></h6>
                    </div>
                    <div class="widget-content">
                        <ul>
<?php
$total = 0;
$pagu = 0;
$anggarans = db_fetch_all($bp, "SELECT * FROM anggaran WHERE status = 1");
foreach ($anggarans as $ag) {
    $serapan = $ag["serapan_anggaran"];
    $pagu = $ag["pagu_anggaran"];
    $total += $serapan;
    $persen = ($serapan > 0 && $pagu > 0) ? round($serapan / $pagu * 100, 1) : 0;
?>
                            <li>
                                <span class="stats-title"><?= htmlspecialchars($ag['akun_anggaran']) ?></span>
                                <span class="stats-complete"><?= $persen ?>%</span>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="<?= $persen ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $persen ?>%;">
                                    </div>
                                </div>
                            </li>
<?php }
$total_persen = ($total > 0 && $pagu > 0) ? round($total / $pagu * 100, 1) : 0;
?>
                            <li>
                                <span class="stats-title">Total Absorption</span>
                                <span class="stats-complete"><?= $total_persen ?>%</span>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?= $total_persen ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $total_persen ?>%;">
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
    
            <script>
                // Maintain Scroll Position
                if (typeof localStorage !== 'undefined') {
                    if (localStorage.getItem('sidebar-left-position') !== null) {
                        var initialPosition = localStorage.getItem('sidebar-left-position'),
                            sidebarLeft = document.querySelector('#sidebar-left .nano-content');
                        
                        sidebarLeft.scrollTop = initialPosition;
                    }
                }
            </script>
            
    
        </div>
    
    </aside>