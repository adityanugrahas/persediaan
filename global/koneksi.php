<?php
/**
 * Database Connection & Global Helpers
 * PostgreSQL via PDO — Persediaan System
 */

// Database configuration for PostgreSQL
$host     = "localhost";
$port     = "5432";
$db_name  = "baperkrw";
$db_user  = "postgres";
$db_pass  = ""; // Update with your password

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db_name";
    $bp = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

date_default_timezone_set('Asia/Jakarta');

// Global date variables for compatibility
$tgl    = date("d");
$bln    = date("m");
$thn    = date("Y");
$now    = date("Y-m-d H:i:s");
$hrini  = date("Y-m-d");
$hari   = date("D");
$bulan  = date("M");
$jam    = date("H:i:s");

/**
 * ---------------------------------------------------------------------------
 * CSRF Protection Helpers
 * ---------------------------------------------------------------------------
 */
function csrf_token(): string {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="_csrf_token" value="' . csrf_token() . '">';
}

function verify_csrf(string $token = null): bool {
    if ($token === null) {
        $token = $_POST['_csrf_token'] ?? '';
    }
    return hash_equals(csrf_token(), $token);
}

/**
 * ---------------------------------------------------------------------------
 * Password Helpers (dual-mode: plaintext → bcrypt migration)
 * ---------------------------------------------------------------------------
 */
function password_safe_hash(string $password): string {
    return password_hash($password, PASSWORD_BCRYPT);
}

function password_safe_verify(string $password, string $hash): bool {
    // If the stored hash looks like a bcrypt hash, use password_verify
    if (str_starts_with($hash, '$2y$') || str_starts_with($hash, '$2b$')) {
        return password_verify($password, $hash);
    }
    // Legacy plaintext comparison (will be removed after migration)
    return ($password === $hash);
}

/**
 * ---------------------------------------------------------------------------
 * Database Helper Functions
 * ---------------------------------------------------------------------------
 */

/**
 * Execute a simple query and return the PDOStatement
 */
function db_query(PDO $pdo, string $sql): PDOStatement {
    return $pdo->query($sql);
}

/**
 * Prepare and execute a parameterised query
 */
function db_execute(PDO $pdo, string $sql, array $params = []): PDOStatement {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

/**
 * Fetch a single row
 */
function db_fetch(PDO $pdo, string $sql, array $params = []): ?array {
    $stmt = db_execute($pdo, $sql, $params);
    $row  = $stmt->fetch();
    return $row ?: null;
}

/**
 * Fetch all rows
 */
function db_fetch_all(PDO $pdo, string $sql, array $params = []): array {
    $stmt = db_execute($pdo, $sql, $params);
    return $stmt->fetchAll();
}

/**
 * Fetch a single column value
 */
function db_fetch_column(PDO $pdo, string $sql, array $params = [], int $col = 0) {
    $stmt = db_execute($pdo, $sql, $params);
    return $stmt->fetchColumn($col);
}

/**
 * Count rows matching a query
 */
function db_count(PDO $pdo, string $sql, array $params = []): int {
    $stmt = db_execute($pdo, $sql, $params);
    return $stmt->rowCount();
}

/**
 * ---------------------------------------------------------------------------
 * File Upload Helper
 * ---------------------------------------------------------------------------
 */
function handle_upload(string $input_name, string $dest_dir, string $prefix = '', int $max_width = 800): string {
    if (empty($_FILES[$input_name]['name'])) {
        return '';
    }

    $file     = $_FILES[$input_name];
    $ext_parts = explode('.', $file['name']);
    $file_ext  = strtolower(end($ext_parts));
    $filename  = ($prefix ?: date('ymdhis')) . '.' . $file_ext;
    $dest_dir  = rtrim($dest_dir, '/') . '/';
    $upload    = $dest_dir . $filename;

    if (!is_dir($dest_dir)) {
        mkdir($dest_dir, 0755, true);
    }

    move_uploaded_file($file['tmp_name'], $upload);

    // Resize JPEG images
    if (in_array($file_ext, ['jpg', 'jpeg']) && $max_width > 0) {
        $info = @getimagesize($upload);
        if ($info && $info[0] > $max_width) {
            $k         = $info[0] / $max_width;
            $newwidth  = (int)($info[0] / $k);
            $newheight = (int)($info[1] / $k);
            $thumb     = imagecreatetruecolor($newwidth, $newheight);
            $source    = imagecreatefromjpeg($upload);
            imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $info[0], $info[1]);
            imagejpeg($thumb, $upload, 85);
            imagedestroy($thumb);
            imagedestroy($source);
        }
    }

    return $filename;
}

/**
 * ---------------------------------------------------------------------------
 * Redirect Helper (replaces JS document.location hacks)
 * ---------------------------------------------------------------------------
 */
function redirect(string $url): void {
    header("Location: $url");
    exit;
}

/**
 * Flash message helper — stores a message in session for display after redirect
 */
function flash(string $type, string $message): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['_flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $flash = $_SESSION['_flash'] ?? null;
    unset($_SESSION['_flash']);
    return $flash;
}
?>