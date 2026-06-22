<?php
session_start();
require_once __DIR__ . '/includes/api.php';

$pageTitle = 'Beranda';
$pageSubtitle = '';
$currentMenu = 'dashboard';

// Fetch Data
$apiResponse = api_get('/titik-risiko');
$dataTitik = $apiResponse['success'] ? $apiResponse['data'] : [];

// Statistics
$total = count($dataTitik);
$rendah = 0;
$sedang = 0;
$tinggi = 0;
$belum_ada_data = 0;

foreach ($dataTitik as $t) {
    $lvl = strtolower($t['level_risiko_awal'] ?? '');
    if ($lvl === 'rendah') $rendah++;
    elseif ($lvl === 'sedang') $sedang++;
    elseif ($lvl === 'tinggi') $tinggi++;
    else $belum_ada_data++;
}

// Fallback to match mockup roughly if empty
if ($total === 0) {
    // If no real data, we can either show 0 or mock to match mockup for visual purpose, but real is 0.
}

$extraHead = '
<style>
.hero-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1rem 0;
}
.hero-text h1 {
    font-size: 2.2rem;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}
.hero-text p {
    font-size: 1.1rem;
    color: #0284c7; /* Blueish text per mockup */
    font-weight: 600;
    text-decoration: underline;
    text-underline-offset: 4px;
}
.hero-image {
    max-width: 450px;
    height: auto;
}
.bottom-banner {
    background: white;
    border-radius: var(--radius-md);
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1.5rem;
    box-shadow: var(--shadow-sm);
}
.banner-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}
.banner-icon {
    width: 60px;
    height: 60px;
    background: var(--primary-color);
    color: white;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}
.banner-text h3 {
    color: var(--primary-color);
    font-size: 1.3rem;
    margin-bottom: 0.25rem;
}
.banner-text p {
    color: var(--text-main);
    font-weight: 500;
    font-size: 0.95rem;
}
.banner-img {
    height: 100px;
    width: auto;
}
@media (max-width: 768px) {
    .hero-section { flex-direction: column; text-align: center; }
    .bottom-banner { flex-direction: column; text-align: center; gap: 1.5rem; }
    .banner-content { flex-direction: column; text-align: center; }
}
</style>
';

include 'includes/header.php';
?>

<!-- Hero Section -->
<div class="hero-section animate-fade-in">
    <div class="hero-text">
        <h1>Selamat Datang di BAMUK!</h1>
        <p>Pantau informasi resiko DBD di lingkungan sekitar Anda.</p>
    </div>
    <div class="hero-image">
        <img src="assets/img/hero_house.png" alt="Houses and Nature" style="width: 100%; height: auto;">
    </div>
</div>

<!-- Stats Container (Light Green) -->
<div class="stats-container animate-fade-in delay-1">
    <h2 class="stats-title">Ringkasan DBD di Sekitarmu</h2>
    
    <div class="stat-grid">
        <!-- Low Risk -->
        <div class="stat-item">
            <div class="stat-header low">
                <i class="ph-fill ph-house-line"></i> Resiko Rendah
            </div>
            <div class="stat-status">Aman</div>
            <div class="stat-value-container">
                <div class="stat-value low"><?= $rendah ?></div>
                <div class="stat-unit">wilayah</div>
            </div>
        </div>
        
        <!-- Medium Risk -->
        <div class="stat-item">
            <div class="stat-header med">
                <i class="ph-fill ph-map-pin"></i> Resiko Sedang
            </div>
            <div class="stat-status">Waspada</div>
            <div class="stat-value-container">
                <div class="stat-value med"><?= $sedang ?></div>
                <div class="stat-unit">wilayah</div>
            </div>
        </div>
        
        <!-- High Risk -->
        <div class="stat-item">
            <div class="stat-header high">
                <i class="ph-fill ph-fire"></i> Resiko Tinggi
            </div>
            <div class="stat-status">Bahaya</div>
            <div class="stat-value-container">
                <div class="stat-value high"><?= $tinggi ?></div>
                <div class="stat-unit">wilayah</div>
            </div>
        </div>
        
        <!-- No Data -->
        <div class="stat-item">
            <div class="stat-header empty">
                <i class="ph-light ph-arrows-clockwise"></i> Belum ada Data
            </div>
            <div class="stat-status">-</div>
            <div class="stat-value-container">
                <div class="stat-value empty"><?= $belum_ada_data ?></div>
                <div class="stat-unit">wilayah</div>
            </div>
        </div>
    </div>
    
    <!-- Bottom Banner -->
    <div class="bottom-banner">
        <div class="banner-content">
            <div class="banner-icon">
                <i class="ph-bold ph-shield-check"></i>
            </div>
            <div class="banner-text">
                <h3>Jaga Lingkungan Cegah DBD!!</h3>
                <p>Lakukan 3M Plus untuk melindungi keluarga dan lingkunga kita.</p>
            </div>
        </div>
        <div>
            <img src="assets/img/family_planting.png" alt="Family Planting" class="banner-img">
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
