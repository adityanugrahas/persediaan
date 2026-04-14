<aside id="sidebar-left" class="sidebar-left">
    <div class="sidebar-header">
        <div style="color:#fff" class="sidebar-title sidebar-default">
            Navigation
        </div>
        <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
            <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <div class="nano">
        <div class="nano-content">
            <nav id="menu" class="nav-main" role="navigation">
                <ul class="nav nav-main">
                    <li class="nav-active">
                        <a href="admin.php">
                            <i class="fa fa-home" aria-hidden="true"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="?p=petugas">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span>Petugas</span>
                        </a>
                    </li>
                    <li>
                        <a href="?p=bagian">
                            <i class="fa fa-building" aria-hidden="true"></i>
                            <span>Unit Kerja / Bagian</span>
                        </a>
                    </li>
                    <li class="nav-parent">
                        <a>
                            <i class="fa fa-list-alt" aria-hidden="true"></i>
                            <span>Master Barang</span>
                        </a>
                        <ul class="nav nav-children">
                            <li><a href="?p=kat">Kategori Persediaan</a></li>
                            <li><a href="?p=stok">Semua Persediaan</a></li>
                        </ul>
                    </li>
                    <li class="nav-parent">
                        <a>
                            <i class="fa fa-archive" aria-hidden="true"></i>
                            <span>Aset / BMN</span>
                        </a>
                        <ul class="nav nav-children">
                            <li><a href="?p=bmn_kat">Kategori BMN</a></li>
                            <li><a href="?p=bmn">Semua Aset BMN</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>

            <hr class="separator" />

            <?php
            $today_date = date("Y-m-d");
            // Assuming we use the same database via $bp. 
            // If the queue system (antrian) is in another db, it should have been defined in koneksi.php
            // But here we'll assume it's part of the main system for migration consistency.
            try {
                $q_antri = db_fetch($bp, "SELECT 
                    COUNT(antrian_no) as jumlah,
                    COUNT(CASE WHEN antrian_status = 'selesai' THEN 1 END) as selesai 
                    FROM no_antrian 
                    WHERE antrian_datang::text LIKE :today", ['today' => $today_date . '%']);
                
                $jumlah = $q_antri['jumlah'] ?? 0;
                $selesai = $q_antri['selesai'] ?? 0;
                $persen = ($jumlah > 0) ? number_format(($selesai / $jumlah * 100), 2) : "0";
            } catch (Exception $e) {
                $jumlah = 0; $selesai = 0; $persen = 0;
            }
            ?>				
            <div class="sidebar-widget widget-stats">
                <div class="widget-header">
                    <h6 style="color:#fff">Jumlah Antrian : <?= $jumlah ?></h6>
                    <div class="widget-toggle">+</div>
                </div>
                <div class="widget-content">
                    <ul>
                        <li>
                            <span class="stats-title">Selesai : <?= $selesai ?></span>
                            <span class="stats-complete" style="color:#fff;"><?= $persen ?> %</span>
                            <div class="progress">
                                <div class="progress-bar progress-bar-primary progress-without-number" role="progressbar" aria-valuenow="<?= $persen ?>" aria-valuemin="0" aria-valuemax="100" style="color:#fff; width: <?= $persen ?>%;">
                                    <span class="sr-only"><?= $persen ?> % Selesai</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</aside>