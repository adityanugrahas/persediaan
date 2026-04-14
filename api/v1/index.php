<?php
/**
 * Persediaan System: Comprehensive REST API (v1)
 * Provides JSON endpoints for inventory, distribution, and statistics.
 */

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Enable error reporting for debugging (turn off in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once "../../global/koneksi.php";

/**
 * Handle OPTIONS request (CORS preflight)
 */
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

/**
 * Basic Authentication Guard
 * In a real production environment, use JWT or persistent API Keys.
 * For this implementation, we check for a valid session OR a static master token for simplicity.
 */
session_start();
$master_token = "mika_persediaan_2026_secret"; // This should be in DB/ENV
$auth_header  = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$is_authenticated = isset($_SESSION['idp']) || (str_replace('Bearer ', '', $auth_header) === $master_token);

if (!$is_authenticated) {
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized access. Please provide a valid session or API token.",
        "code" => 401
    ]);
    http_response_code(401);
    exit;
}

// Simple router based on GET 'resource'
$resource = $_GET['resource'] ?? '';
$action   = $_GET['action'] ?? 'list';
$id       = $_GET['id'] ?? null;

$response = [
    "status" => "success",
    "timestamp" => date('c'),
    "data" => null
];

try {
    switch ($resource) {
        case 'stats':
            get_stats();
            break;
        case 'inventory':
            get_inventory($id);
            break;
        case 'categories':
            get_categories();
            break;
        case 'alerts':
            get_alerts();
            break;
        case 'activity':
            get_activity();
            break;
        default:
            throw new Exception("Resource not found or endpoint not implemented.", 404);
    }
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage(),
        "code" => $e->getCode() ?: 500
    ]);
    exit;
}

/**
 * Resource: Statistics
 */
function get_stats() {
    global $bp, $response;
    
    $stats = [
        "total_items" => (int)db_fetch_column($bp, "SELECT COUNT(*) FROM stok_barang WHERE aktif='ya'"),
        "total_categories" => (int)db_fetch_column($bp, "SELECT COUNT(*) FROM kategori"),
        "low_stock_count" => (int)db_fetch_column($bp, "SELECT COUNT(*) FROM stok_barang WHERE jumlah_stok <= stok_minimal AND aktif='ya'"),
        "pending_requests" => (int)db_fetch_column($bp, "SELECT COUNT(*) FROM stok_inout WHERE jenis='out' AND status='0'"),
        "total_distribution" => (int)db_fetch_column($bp, "SELECT SUM(jml_out) FROM stok_inout WHERE status='2'")
    ];
    
    $response['data'] = $stats;
    echo json_encode($response);
}

/**
 * Resource: Inventory
 */
function get_inventory($id) {
    global $bp, $response;
    
    if ($id) {
        $item = db_fetch($bp, "SELECT * FROM stok_barang WHERE id_barang = :id", ['id' => $id]);
        if (!$item) throw new Exception("Item not found.", 404);
        $response['data'] = $item;
    } else {
        $query = "SELECT s.*, k.nama_kat FROM stok_barang s 
                  LEFT JOIN kategori k ON s.kategori = k.id_kat 
                  WHERE s.aktif = 'ya' ORDER BY s.nama_barang";
        $response['data'] = db_fetch_all($bp, $query);
    }
    echo json_encode($response);
}

/**
 * Resource: Categories
 */
function get_categories() {
    global $bp, $response;
    $response['data'] = db_fetch_all($bp, "SELECT * FROM kategori ORDER BY nama_kat");
    echo json_encode($response);
}

/**
 * Resource: Alerts (Low Stock)
 */
function get_alerts() {
    global $bp, $response;
    $query = "SELECT * FROM stok_barang WHERE jumlah_stok <= stok_minimal AND aktif = 'ya' ORDER BY jumlah_stok ASC";
    $response['data'] = db_fetch_all($bp, $query);
    echo json_encode($response);
}

/**
 * Resource: Recent Activity (Stock Movements)
 */
function get_activity() {
    global $bp, $response;
    $query = "SELECT i.*, b.nama_barang, p.nama as nama_petugas 
              FROM stok_inout i 
              JOIN stok_barang b ON i.id_barang = b.id_barang 
              JOIN petugas p ON i.petugas = p.id_petugas 
              ORDER BY i.tgl DESC LIMIT 20";
    $response['data'] = db_fetch_all($bp, $query);
    echo json_encode($response);
}
