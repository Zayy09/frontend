<?php
$pageTitle = $pageTitle ?? 'BAMUK';
$currentMenu = $currentMenu ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Pemantauan Risiko Demam Berdarah Berbasis Lingkungan (BAMUK)">
    <title><?= htmlspecialchars($pageTitle) ?> | BAMUK</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?= $extraHead ?? '' ?>
</head>
<body>

<!-- Top Navbar -->
<header class="top-navbar">
    <div class="nav-brand">
        <a href="index.php" class="brand-logo-container">
            <img src="assets/img/Logo.png" alt="BAMUK Logo" class="brand-logo-img">
            <div class="brand-text">
                <span class="brand-text-top">Basmi</span>
                <span class="brand-text-top">Nyamuk</span>
            </div>
        </a>
    </div>
    
    <button class="mobile-toggle" onclick="document.getElementById('navMenu').classList.toggle('active')">
        <i class="ph-bold ph-list"></i>
    </button>
    
    <nav class="nav-menu" id="navMenu">
        <a href="index.php" class="nav-item <?= $currentMenu === 'dashboard' ? 'active' : '' ?>">
            <i class="ph-fill ph-house"></i>
            <span>Beranda</span>
        </a>
        <a href="titik-risiko.php" class="nav-item <?= $currentMenu === 'titik' ? 'active' : '' ?>">
            <i class="ph-fill ph-map-pin"></i>
            <span>Peta</span>
        </a>
        <a href="edukasi.php" class="nav-item <?= $currentMenu === 'edukasi' ? 'active' : '' ?>">
            <i class="ph-fill ph-head-profile"></i>
            <span>Edukasi</span>
        </a>
        <a href="search.php" class="nav-item <?= $currentMenu === 'search' ? 'active' : '' ?>">
            <i class="ph-fill ph-magnifying-glass"></i>
            <span>Search</span>
        </a>

    </nav>
</header>

<main class="main-content">
    
    <!-- Flash Messages (if any) -->
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="glass-card animate-fade-in" style="margin-bottom: 1.5rem; background: var(--risk-low-bg); color: var(--risk-low-text); padding: 1rem; border: 1px solid #86efac; display: flex; align-items: center; gap: 0.75rem;">
            <i class="ph-fill ph-check-circle" style="font-size: 1.5rem;"></i>
            <div style="font-weight: 500;"><?= htmlspecialchars($_SESSION['flash_message']) ?></div>
        </div>
        <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>
