<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - HMJ-SI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-text: #94a3b8;
            --sidebar-active: #ffffff;
            --main-bg: #f8fafc;
        }
        body { 
            background-color: var(--main-bg); 
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #1e293b;
        }
        
        /* Sidebar Styles */
        .sidebar { 
            min-height: 100vh; 
            background-color: var(--sidebar-bg); 
            color: white; 
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
        }
        .sidebar-desktop {
            z-index: 1000;
        }
        .sidebar a { 
            color: var(--sidebar-text); 
            text-decoration: none; 
            padding: 12px 24px; 
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }
        .sidebar a:hover { 
            color: var(--sidebar-active); 
            background-color: var(--sidebar-hover); 
        }
        .sidebar a.active { 
            color: var(--sidebar-active); 
            background-color: #3b82f6; 
            font-weight: 600;
        }
        .sidebar a i { font-size: 1.2rem; }
        
        /* Layout Fixes */
        @media (min-width: 768px) {
            main { margin-left: 16.666667%; } /* Match col-md-2 */
        }
        
        /* Mobile Navbar */
        .admin-nav {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.75rem 1rem;
        }

        /* Card Improvements */
        .card {
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 0.75rem;
        }
        .card-header {
            background-color: white !important;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem;
        }

        /* Responsive Table Adjustments */
        .table-responsive {
            border-radius: 0.75rem;
        }
        .table thead th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            color: #64748b;
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .table tbody td {
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
        }

        @media (max-width: 576px) {
            .table { font-size: 0.85rem; }
            .btn-sm { font-size: 0.75rem; padding: 0.25rem 0.5rem; }
            h1.h2 { font-size: 1.5rem; }
            .card-body { padding: 1rem; }
            .ms-sm-auto { margin-left: 0 !important; }
        }
        
        /* Offcanvas styling */
        .offcanvas { border: none !important; }
        .offcanvas-header { padding: 1.5rem; }

        /* Custom Scrollbar for Table */
        .table-responsive::-webkit-scrollbar {
            height: 6px;
        }
        .table-responsive::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .table-responsive::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Helpers */
        @media (max-width: 576px) {
            .d-none-xs { display: none !important; }
        }
    </style>
</head>
<!-- Mobile Navbar -->
<nav class="navbar navbar-expand-md admin-nav d-md-none sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">Admin HMJ-SI</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Desktop -->
        <nav class="col-md-2 d-none d-md-block sidebar p-0 position-fixed">
            <div class="p-4">
                <h5 class="fw-bold text-white mb-0">Admin HMJ-SI</h5>
            </div>
            <div class="mt-3">
                <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="members.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'members.php' ? 'active' : ''; ?>">
                    <i class="bi bi-people"></i> Manajemen Pengurus
                </a>
                <a href="cadres.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'cadres.php' ? 'active' : ''; ?>">
                    <i class="bi bi-person-badge"></i> Manajemen Kader
                </a>
                <a href="gallery.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'gallery.php' ? 'active' : ''; ?>">
                    <i class="bi bi-images"></i> Manajemen Galeri
                </a>
                <a href="events.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'events.php' ? 'active' : ''; ?>">
                    <i class="bi bi-calendar-event"></i> Manajemen Event
                </a>
                <a href="news.php" class="<?php echo strpos(basename($_SERVER['PHP_SELF']), 'news') === 0 ? 'active' : ''; ?>">
                    <i class="bi bi-newspaper"></i> Manajemen Berita
                </a>
                <a href="messages.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'messages.php' ? 'active' : ''; ?>">
                    <i class="bi bi-chat-left-text"></i> Manajemen Pesan
                </a>
                <hr class="mx-3 border-secondary opacity-25">
                <a href="logout.php" class="text-danger">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </nav>

        <!-- Sidebar Mobile (Offcanvas) -->
        <div class="offcanvas offcanvas-start bg-dark text-white d-md-none" tabindex="-1" id="sidebarOffcanvas" style="width: 250px;">
            <div class="offcanvas-header border-bottom border-secondary">
                <h5 class="offcanvas-title fw-bold">Admin HMJ-SI</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body p-0 sidebar">
                <div class="mt-3">
                    <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="members.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'members.php' ? 'active' : ''; ?>">
                        <i class="bi bi-people"></i> Manajemen Pengurus
                    </a>
                    <a href="cadres.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'cadres.php' ? 'active' : ''; ?>">
                        <i class="bi bi-person-badge"></i> Manajemen Kader
                    </a>
                    <a href="gallery.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'gallery.php' ? 'active' : ''; ?>">
                        <i class="bi bi-images"></i> Manajemen Galeri
                    </a>
                    <a href="events.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'events.php' ? 'active' : ''; ?>">
                        <i class="bi bi-calendar-event"></i> Manajemen Event
                    </a>
                    <a href="news.php" class="<?php echo strpos(basename($_SERVER['PHP_SELF']), 'news') === 0 ? 'active' : ''; ?>">
                        <i class="bi bi-newspaper"></i> Manajemen Berita
                    </a>
                    <a href="messages.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'messages.php' ? 'active' : ''; ?>">
                        <i class="bi bi-chat-left-text"></i> Manajemen Pesan
                    </a>
                    <hr class="mx-3 border-secondary opacity-25">
                    <a href="logout.php" class="text-danger">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto px-md-4 py-4">
