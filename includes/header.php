<?php
// Dynamic path resolution helper
$project_root = realpath(dirname(__DIR__));
$script_path = realpath($_SERVER['SCRIPT_FILENAME']);
$relative_path = '';

if ($project_root && $script_path) {
    $project_root = str_replace('\\', '/', $project_root);
    $script_path = str_replace('\\', '/', $script_path);
    
    $root_parts = array_filter(explode('/', $project_root));
    $script_parts = array_filter(explode('/', dirname($script_path)));
    
    // Normalize array indexes
    $root_parts = array_values($root_parts);
    $script_parts = array_values($script_parts);
    
    $depth = count($script_parts) - count($root_parts);
    if ($depth > 0) {
        $relative_path = str_repeat('../', $depth);
    }
}
if (!defined('BASE_PATH')) {
    define('BASE_PATH', $relative_path);
}

require_once $project_root . '/config/db.php';
require_once $project_root . '/includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeRoads – 3D Road Accident Awareness & Traffic Rules Education Platform</title>
    <meta name="description" content="An interactive 3D web platform to learn traffic signs, road rules, safety guides, and test your knowledge. Let's make roads safer together.">
    <meta name="author" content="SafeRoads">
    
    <!-- Google Fonts: Outfit (headings) & Inter (body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Leaflet.js CSS (OpenStreetMap) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    
    <!-- Custom Style Sheet -->
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/style.css">
    
    <!-- SweetAlert2 (Beautiful alerts) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Script to set theme early to prevent screen flash -->
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-bs-theme', theme);
            document.documentElement.className = theme === 'dark' ? 'dark-mode' : 'light-mode';
        })();
    </script>
</head>
<body>
<?php include $project_root . '/includes/navbar.php'; ?>
<main class="main-content">
