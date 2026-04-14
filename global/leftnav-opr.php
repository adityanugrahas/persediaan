<aside id="sidebar-left" class="sidebar-left">
				
    <div class="sidebar-header">
        <div class="sidebar-title text-light">
            Navigation
        </div>
        <div class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>
				
    <div class="nano">
            <div class="nano-content">
                <nav id="menu" class="nav-main" role="navigation">
                
                    <ul class="nav nav-main">
                        <li>
                            <a class="nav-link" href="opr.php">
                                <i class="fas fa-home"></i>
                                <span>DASHBOARD</span>
                            </a>                        
                        </li>
                        <li class="nav-parent">
                            <a class="nav-link" href="#">   
                            <i class="fas fa-boxes"></i>
                            <span>PERSEDIAAN</span>
                            </a>
                            <ul class="nav nav-children">
                            <?php
                                $skat = db_fetch_all($bp, "SELECT * FROM kategori ORDER BY nama_kat");
                                if (count($skat) > 0) {
                                    echo "<li><a class='nav-link' href='?p=stok_all'>Semua Barang</a></li>";
                                    foreach ($skat as $kat) {
                                        echo "<li><a class='nav-link' href='?p=stok_kat&kat=" . htmlspecialchars($kat['id_kat']) . "&kat_nm=" . htmlspecialchars($kat['nama_kat']) . "'>" . htmlspecialchars($kat['nama_kat']) . "</a></li>";
                                    }
                                }
                            ?>
                            </ul>
                        </li>
                        <li>
                            <a class="nav-link" href="?p=permintaan">
                                <i class="fas fa-shopping-basket" aria-hidden="true"></i>
                                <span>PERMINTAAN
                                <?php
                                $jpt = (int)db_fetch_column($bp, "SELECT COUNT(*) FROM stok_inout WHERE id_seksi = :seksi AND status = '1'", ['seksi' => $_SESSION['seksip']]);
                                if ($jpt > 0) {
                                    echo "<span class='float-right badge badge-danger'>{$jpt}</span>";
                                }
                                ?></span>
                            </a>                        
                        </li>
                        <li>
                            <a class="nav-link" href="?p=pesanan">
                                <i class="fas fa-clipboard-list" aria-hidden="true"></i>
                                <span>PESANAN
                                <?php
                                if (isset($jps) && $jps > 0) {
                                    echo "<span class='float-right badge badge-danger'>{$jps}</span>";
                                }
                                ?>
                                </span>
                            </a>                        
                        </li>
                        <li>
                            <a class="nav-link" href="?p=profil">
                                <i class="fas fa-user-cog" aria-hidden="true"></i>
                                <span>SETTING AKUN</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="?p=logout">
                                <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                                <span>KELUAR</span>
                            </a>                        
                        </li>
                    </ul>
                </nav>
   
                <hr class="separator" />
 <?php
 $ta = db_fetch($bp, "SELECT tahun_anggaran FROM anggaran WHERE status = 1 LIMIT 1");
 $tha = !empty($ta['tahun_anggaran']) ? "Tahun Anggaran " . htmlspecialchars($ta['tahun_anggaran']) : "";
 ?>   
                <div class="sidebar-widget widget-stats">
                    <div class="widget-header">
                        <h6 class="text-light"><?= $tha ?></h6>
                        <div class="widget-toggle">+</div>
                    </div>
                    <div class="widget-content">
                        <ul>
<?php
$total = 0;
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
                                    <div class="progress-bar progress-bar-primary progress-without-number" role="progressbar" aria-valuenow="<?= $persen ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $persen ?>%;">
                                        <span class="sr-only"><?= $persen ?> % Complete</span>
                                    </div>
                                </div>
                            </li>
<?php }
$total_persen = ($total > 0 && $pagu > 0) ? round($total / $pagu * 100, 1) : 0;
?>
                            <li>
                                <span class="stats-title">Total</span>
                                <span class="stats-complete"><?= $total_persen ?> %</span>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-warning progress-without-number" role="progressbar" aria-valuenow="<?= $total_persen ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $total_persen ?>%;">
                                        <span class="sr-only"><?= $total_persen ?> % Complete</span>
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