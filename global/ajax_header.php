<?php
/**
 * AJAX Header Helper
 * Detects if a fragment is requested via AJAX or Standalone Browser.
 */
$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if (!$is_ajax): 
    // Standard branding fetch if not already in context
    if (!isset($set) && isset($bp)) {
        $set = db_fetch($bp, "SELECT logo_header, favicon FROM setting LIMIT 1");
    }
?>
<!DOCTYPE html>
<html lang="id" class="fixed">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Standalone View</title>
    <link rel="shortcut icon" href="../img/<?= htmlspecialchars($set['favicon'] ?? 'favicon.png') ?>" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="../vendor/font-awesome/css/all.min.css" />
    <link rel="stylesheet" href="../css/theme.css" />
    <link rel="stylesheet" href="../css/custom.css">
</head>
<body style="background: radial-gradient(circle at 0% 0%, #17153b 0%, #0c1222 50%, #010410 100%) !important; min-height: 100vh; padding: 40px 0;">
<?php endif; ?>
