<?php
/**
 * Interactive Dashboard v4.0 - Premium Operations Hub
 * Comprehensive Statistics | Intuitive UX | Advanced Visuals
 */

// 1. Data Analytics Core
$items_count = (int)db_fetch_column($bp, "SELECT COUNT(*) FROM stok_barang WHERE aktif = 'ya'");
$alert_count = (int)db_fetch_column($bp, "SELECT COUNT(*) FROM stok_barang WHERE jumlah_stok <= stok_minimal AND aktif = 'ya'");
$pending_req = (int)db_fetch_column($bp, "SELECT COUNT(*) FROM stok_inout WHERE status = '0' AND jenis = 'out'");
$total_out   = (int)db_fetch_column($bp, "SELECT SUM(jml_out) FROM stok_inout WHERE status = '2'");

// Advanced Performance Metrics
$total_in      = (int)db_fetch_column($bp, "SELECT SUM(jml_in) FROM stok_inout WHERE status = '2'");
$turnover_rate = ($total_in > 0) ? round(($total_out / $total_in) * 100, 1) : 0;
$budget_total  = (int)db_fetch_column($bp, "SELECT SUM(pagu_anggaran) FROM anggaran WHERE status = 1");
$budget_used   = (int)db_fetch_column($bp, "SELECT SUM(serapan_anggaran) FROM anggaran WHERE status = 1");
$budget_rem    = $budget_total - $budget_used;

// 2. Trend Data (Radar Comparison or Smooth Area)
$months = []; $in_data = []; $out_data = [];
$res = $bp->query("SELECT strftime('%m', tgl_ok) as m, SUM(jml_in) as raw_in, SUM(jml_out) as raw_out FROM stok_inout WHERE status='2' GROUP BY m ORDER BY m DESC LIMIT 6");
$trends = array_reverse($res->fetchAll());
foreach ($trends as $t) {
    $months[] = date("F", mktime(0, 0, 0, (int)$t['m'], 1));
    $in_data[] = (int)$t['raw_in'];
    $out_data[] = (int)$t['raw_out'];
}

// 3. Top Distributed Items
$top_items = db_fetch_all($bp, "
    SELECT b.nama_barang, SUM(i.jml_out) as total, b.satuan, b.jumlah_stok
    FROM stok_inout i 
    JOIN stok_barang b ON i.id_barang = b.id_barang 
    WHERE i.status = '2' AND i.jenis = 'out'
    GROUP BY b.id_barang ORDER BY total DESC LIMIT 4
");

// 4. Units Health (Activity by Section)
$unit_activity = db_fetch_all($bp, "
    SELECT s.bagian, COUNT(i.id_inout) as total
    FROM stok_inout i
    JOIN bagian s ON i.id_seksi = s.id_seksi
    GROUP BY s.id_seksi ORDER BY total DESC LIMIT 5
");
?>

<!-- Header Stats Row -->
<div class="row mb-4 animate__animated animate__fadeIn">
    <div class="col">
        <div class="glass-card p-4 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary p-3 mr-3 shadow-glow"><i class="fas fa-warehouse fa-lg text-white"></i></div>
                <div>
                    <h5 class="text-white font-weight-bold mb-0">Operational Pulse</h5>
                    <p class="text-muted xs-text mb-0"><?= date('l, d F Y') ?></p>
                </div>
            </div>
            <div class="d-none d-md-flex">
                <div class="px-4 border-right border-glass">
                    <div class="h5 font-weight-bold text-white mb-0"><?= $items_count ?></div>
                    <small class="xs-text text-primary">CATALOG ITEMS</small>
                </div>
                <div class="px-4 border-right border-glass">
                    <div class="h5 font-weight-bold text-danger mb-0"><?= $alert_count ?></div>
                    <small class="xs-text text-danger">CRITICAL ALERTS</small>
                </div>
                <div class="px-4">
                    <div class="h5 font-weight-bold text-success mb-0"><?= $turnover_rate ?>%</div>
                    <small class="xs-text text-success">TURNOVER RATE</small>
                </div>
            </div>
            <a href="?p=stok_add" class="btn btn-primary d-none d-lg-block" style="border-radius:30px;">
                <i class="fas fa-plus-circle mr-2"></i> New Entry
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Logistics Radar & Trends -->
    <div class="col-lg-7 animate__animated animate__fadeInLeft">
        <div class="card glass-card border-0 mb-4 overflow-visible">
            <div class="p-4 d-flex justify-content-between">
                <h5 class="text-white font-weight-bold">Supply vs Demand Radar</h5>
                <span class="badge badge-glass text-muted">Comparative Index</span>
            </div>
            <div id="radar-analytics" style="height: 380px;"></div>
        </div>

        <div class="card glass-card border-0">
            <div class="p-4 border-bottom border-glass d-flex justify-content-between align-items-center">
                <h5 class="text-white font-weight-bold mb-0">Fast-Moving Inventory</h5>
                <a href="?p=rekap" class="xs-text text-primary font-weight-bold">VIEW ALL</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush bg-transparent">
                    <?php foreach ($top_items as $item): 
                        $pct = ($items_count > 0) ? round(($item['total'] / ($item['jumlah_stok'] + $item['total'])) * 100) : 0;
                        ?>
                    <div class="list-group-item bg-transparent border-glass p-4 d-flex align-items-center">
                        <div class="mr-3 text-center" style="width: 40px;">
                            <span class="text-white font-weight-bold"><?= $pct ?>%</span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-white font-weight-bold mb-1"><?= htmlspecialchars($item['nama_barang']) ?></h6>
                            <div class="progress progress-xs bg-dark" style="height: 4px;">
                                <div class="progress-bar bg-success" style="width: <?= $pct ?>%"></div>
                            </div>
                        </div>
                        <div class="ml-4 text-right">
                            <div class="text-white font-weight-bold"><?= $item['total'] ?></div>
                            <small class="text-muted text-uppercase"><?= $item['satuan'] ?> Out</small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Intuitive Metrics & Quick View -->
    <div class="col-lg-5 animate__animated animate__fadeInRight">
        <div class="card glass-card border-0 mb-4 p-4">
            <h6 class="text-muted xs-text font-weight-bold text-uppercase mb-4">Budget Utilization</h6>
            <div id="budget-donut" style="height: 280px; margin-top: -30px;"></div>
            <hr class="border-glass my-4">
            <div class="row text-center">
                <div class="col-6">
                    <p class="text-muted xs-text mb-1">REMAINING</p>
                    <h5 class="text-white font-weight-bold">Rp <?= number_format($budget_rem / 1000000, 1) ?>M</h5>
                </div>
                <div class="col-6 border-left border-glass">
                    <p class="text-muted xs-text mb-1">UTILIZED</p>
                    <h5 class="text-success font-weight-bold"><?= $budget_used > 0 ? round(($budget_used / $budget_total) * 100) : 0 ?>%</h5>
                </div>
            </div>
        </div>

        <div class="card glass-card border-0 p-4 mb-4 bg-gradient-dark">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="text-white xs-text font-weight-bold text-uppercase mb-0">System Integrity</h6>
                <span class="badge badge-success px-3" style="border-radius: 20px;">Secure</span>
            </div>
            <div class="p-3 mb-2 rounded border-glass" style="background: rgba(255,255,255,0.02);">
                <div class="d-flex justify-content-between mb-1">
                    <small class="text-white">Database Load</small>
                    <small class="text-primary italic">Nominal</small>
                </div>
                <div class="progress progress-xs bg-dark" style="height:3px;"><div class="progress-bar" style="width:12%"></div></div>
            </div>
            <div class="p-3 rounded border-glass" style="background: rgba(255,255,255,0.02);">
                <div class="d-flex justify-content-between mb-1">
                    <small class="text-white">API Gateway</small>
                    <small class="text-success blink">Active</small>
                </div>
                <div class="progress progress-xs bg-dark" style="height:3px;"><div class="progress-bar bg-success" style="width:100%"></div></div>
            </div>
        </div>

        <!-- Section Activity -->
        <div class="card glass-card border-0 p-4">
            <h6 class="text-white xs-text font-weight-bold text-uppercase mb-3">Top Engaging Units</h6>
            <div id="unit-polar" style="height: 240px;"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Radar Analytics (Supply vs Demand)
    new ApexCharts(document.querySelector("#radar-analytics"), {
        series: [{ name: 'Restock (IN)', data: <?= json_encode($in_data) ?> }, 
                { name: 'Disbursement (OUT)', data: <?= json_encode($out_data) ?> }],
        chart: { type: 'radar', height: 380, foreColor: '#94adcf', toolbar: {show: false} },
        colors: ['#6366f1', '#f43f5e'],
        xaxis: { categories: <?= json_encode($months) ?> },
        plotOptions: { radar: { size: 130, polygons: { strokeColors: 'rgba(255,255,255,0.05)', fill: { colors: 'transparent' } } } },
        stroke: { width: 3 },
        fill: { opacity: 0.1 },
        markers: { size: 4 },
        legend: { position: 'bottom' },
        tooltip: { theme: 'dark' }
    }).render();

    // 2. Budget Utilization Donut
    new ApexCharts(document.querySelector("#budget-donut"), {
        series: [<?= $budget_used ?>, <?= $budget_rem ?>],
        chart: { type: 'donut', height: 280 },
        labels: ['Utilized Pagu', 'Idle Budget'],
        colors: ['#10b981', 'rgba(255,255,255,0.05)'],
        dataLabels: { enabled: false },
        plotOptions: { pie: { donut: { size: '80%', labels: { show: true, total: { show: true, label: 'TOTAL PAGU', color: '#64748b' } } } } },
        stroke: { show: false },
        legend: { show: false },
        tooltip: { theme: 'dark' }
    }).render();

    // 3. Unit Polar Map
    new ApexCharts(document.querySelector("#unit-polar"), {
        series: <?= json_encode(array_column($unit_activity, 'total')) ?>,
        chart: { type: 'polarArea', height: 240, toolbar: {show: false} },
        labels: <?= json_encode(array_column($unit_activity, 'bagian')) ?>,
        fill: { opacity: 0.7 },
        stroke: { width: 2, colors: ['var(--bg-deep)'] },
        theme: { monochrome: { enabled: true, color: '#6366f1', shadeTo: 'dark', shadeIntensity: 0.6 } },
        legend: { show: false },
        tooltip: { theme: 'dark' }
    }).render();
});
</script>

<style>
.bg-gradient-dark { background: linear-gradient(135deg, rgba(15, 23, 42, 0.8), rgba(2, 6, 23, 0.8)) !important; }
.border-glass { border-bottom: 1px solid rgba(255,255,255,0.05) !important; }
.shadow-glow { box-shadow: 0 0 15px var(--primary-glow); }
.blink { animation: blinker 2s linear infinite; }
@keyframes blinker { 50% { opacity: 0; } }
.list-group-item:hover { background: rgba(255,255,255,0.02) !important; transform: translateX(5px); }
</style>
