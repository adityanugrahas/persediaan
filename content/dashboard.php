<?php
/**
 * Interactive Dashboard Statistics
 * Glassmorphism Design with ApexCharts
 */

// 1. Data Summary Fetching
$total_items = (int)db_fetch_column($bp, "SELECT COUNT(*) FROM stok_barang WHERE aktif = 'ya'");
$low_stock   = (int)db_fetch_column($bp, "SELECT COUNT(*) FROM stok_barang WHERE jumlah_stok <= stok_minimal AND aktif = 'ya'");
$pending_req = (int)db_fetch_column($bp, "SELECT COUNT(DISTINCT kode_nota) FROM stok_inout WHERE status = '1'");
$total_dist  = (int)db_fetch_column($bp, "SELECT SUM(jml_out) FROM stok_inout WHERE status = '2'");

// 2. Line Chart Data (Last 6 Months)
// Using SQLite strftime since local dev uses SQLite. If PG, adapt accordingly.
$months = [];
$in_data = [];
$out_data = [];

$trend_stmt = $bp->query("
    SELECT strftime('%Y-%m', tgl_ok) as bulan, SUM(jml_in) as total_in, SUM(jml_out) as total_out 
    FROM stok_inout 
    WHERE status = '2' AND tgl_ok IS NOT NULL
    GROUP BY bulan 
    ORDER BY bulan DESC 
    LIMIT 6
");
$trends = array_reverse($trend_stmt->fetchAll());

foreach ($trends as $t) {
    $months[]   = date("M Y", strtotime($t['bulan']));
    $in_data[]  = (int)$t['total_in'];
    $out_data[] = (int)$t['total_out'];
}

// 3. Category Distribution (Pie Chart)
$cat_stmt = $bp->query("
    SELECT k.nama_kat, COUNT(b.id_barang) as total
    FROM stok_barang b
    JOIN kategori k ON b.kategori = k.id_kat
    WHERE b.aktif = 'ya'
    GROUP BY k.nama_kat
    LIMIT 5
");
$cats = $cat_stmt->fetchAll();
$cat_labels = [];
$cat_series = [];
foreach ($cats as $c) {
    $cat_labels[] = $c['nama_kat'];
    $cat_series[] = (int)$c['total'];
}
?>

<div class="row mb-4 animate__animated animate__fadeIn">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-modern glass-card shadow-lg" style="border-left: 4px solid var(--primary);">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">TOTAL PRODUK</div>
                        <div class="h3 mb-0 font-weight-bold text-white"><?= $total_items ?> <small class="text-muted">Item</small></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-boxes fa-2x text-gray-300" style="opacity:0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-modern glass-card shadow-lg" style="border-left: 4px solid var(--danger);">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">STOK MENIPIS</div>
                        <div class="h3 mb-0 font-weight-bold text-white"><?= $low_stock ?> <small class="text-muted">Alerts</small></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-danger" style="opacity:0.6; filter: drop-shadow(0 0 10px rgba(220,53,69,0.5));"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-modern glass-card shadow-lg" style="border-left: 4px solid var(--warning);">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">PENDING REQUEST</div>
                        <div class="h3 mb-0 font-weight-bold text-white"><?= $pending_req ?> <small class="text-muted">Grup</small></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-paper-plane fa-2x text-warning" style="opacity:0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-modern glass-card shadow-lg" style="border-left: 4px solid var(--success);">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">TOTAL DISTRIBUSI</div>
                        <div class="h3 mb-0 font-weight-bold text-white"><?= number_format($total_dist) ?> <small class="text-muted">Unit</small></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-truck-loading fa-2x text-success" style="opacity:0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
    <!-- Line Chart: Stock Activity -->
    <div class="col-lg-8">
        <div class="card card-modern glass-card mb-4">
            <header class="card-header d-flex justify-content-between align-items-center">
                <h2 class="card-title h5 font-weight-bold mb-0">Trend Aktivitas Stok</h2>
                <span class="badge badge-primary">6 Bulan Terakhir</span>
            </header>
            <div class="card-body">
                <div id="chart-trends" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>

    <!-- Pie Chart: Category Mix -->
    <div class="col-lg-4">
        <div class="card card-modern glass-card mb-4">
            <header class="card-header">
                <h2 class="card-title h5 font-weight-bold">Distribusi Kategori</h2>
            </header>
            <div class="card-body">
                <div id="chart-categories" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
    <div class="col-12">
        <div class="card card-modern glass-card p-4 d-flex flex-row align-items-center justify-content-between">
            <div>
                <h4 class="mb-1 text-white">Butuh Laporan Lengkap?</h4>
                <p class="text-muted small mb-0">Klik tombol di samping untuk mengekspor rekapitulasi data mutasi barang per periode.</p>
            </div>
            <a href="?p=rekap&f=semua" class="btn btn-primary px-5" style="border-radius: 30px; box-shadow: 0 10px 20px var(--primary-glow);">
                <i class="fas fa-file-pdf mr-2"></i> Buka Rekapitulasi
            </a>
        </div>
    </div>
</div>

<!-- Chart Scripts -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Trend Activity Line Chart
    var optionsTrends = {
        series: [{
            name: 'Stok Masuk',
            data: <?= json_encode($in_data) ?>
        }, {
            name: 'Stok Keluar',
            data: <?= json_encode($out_data) ?>
        }],
        chart: {
            height: 350,
            type: 'area',
            toolbar: { show: false },
            background: 'transparent',
            foreColor: '#9ca3af'
        },
        colors: ['#3b82f6', '#ef4444'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [20, 100]
            }
        },
        grid: {
            borderColor: 'rgba(255,255,255,0.05)',
            xaxis: { lines: { show: true } }
        },
        xaxis: {
            categories: <?= json_encode($months) ?>,
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: { labels: { offsetX: -10 } },
        legend: { position: 'top', horizontalAlign: 'right' },
        tooltip: { theme: 'dark', x: { show: true } }
    };

    var chartTrends = new ApexCharts(document.querySelector("#chart-trends"), optionsTrends);
    chartTrends.render();

    // 2. Category Pie Chart
    var optionsCats = {
        series: <?= json_encode($cat_series) ?>,
        chart: {
            height: 380,
            type: 'donut',
            background: 'transparent',
            foreColor: '#9ca3af'
        },
        labels: <?= json_encode($cat_labels) ?>,
        colors: ['#3b82f6', '#f59e0b', '#10b981', '#ef4444', '#8b5cf6'],
        plotOptions: {
            pie: {
                donut: {
                    size: '75%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'KATEGORI',
                            color: '#fff',
                            fontSize: '12px'
                        },
                        value: {
                            color: '#fff',
                            fontSize: '24px',
                            fontWeight: 700
                        }
                    }
                }
            }
        },
        legend: { position: 'bottom' },
        stroke: { show: false },
        tooltip: { theme: 'dark' }
    };

    var chartCats = new ApexCharts(document.querySelector("#chart-categories"), optionsCats);
    chartCats.render();
});
</script>

<style>
.glass-card {
    background: rgba(255, 255, 255, 0.02) !important;
    backdrop-filter: blur(15px) !important;
    -webkit-backdrop-filter: blur(15px) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    border-radius: 16px !important;
}
.card-modern .card-header {
    background: transparent !important;
    border-bottom: 1px solid rgba(255,255,255,0.05) !important;
    padding: 20px 25px !important;
}
.card-modern .card-title {
    color: #fff !important;
    font-size: 1.1rem;
    letter-spacing: -0.02em;
}
.text-xs { font-size: 0.75rem; letter-spacing: 0.05em; }
</style>
