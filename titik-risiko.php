<?php
session_start();
require_once __DIR__ . '/includes/api.php';

$pageTitle = 'Daftar Titik Risiko';
$pageSubtitle = 'Pantau titik risiko demam berdarah di lingkungan';
$currentMenu = 'titik';

// Get Filter Level
$filterLevel = isset($_GET['level']) ? strtolower($_GET['level']) : '';

// Fetch Data Based on Filter
if ($filterLevel && in_array($filterLevel, ['rendah', 'sedang', 'tinggi'])) {
    $apiResponse = api_get('/titik-risiko/level/' . $filterLevel);
} else {
    $apiResponse = api_get('/titik-risiko');
    $filterLevel = ''; // Reset if invalid
}

$dataTitik = $apiResponse['success'] ? $apiResponse['data'] : [];

$extraHead = '
<style>
.filter-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 2rem;
    overflow-x: auto;
    padding-bottom: 0.5rem;
}
.filter-tab {
    padding: 0.6rem 1.2rem;
    border-radius: 999px;
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--text-muted);
    background: white;
    border: 1px solid #e2e8f0;
    white-space: nowrap;
    transition: all 0.2s;
}
.filter-tab:hover {
    background: var(--light-bg);
}
.filter-tab.active {
    background: var(--dark-surface);
    color: white;
    border-color: var(--dark-surface);
    box-shadow: var(--shadow-sm);
}

.risk-card {
    display: flex;
    flex-direction: column;
    height: 100%;
}
.risk-card-body {
    flex: 1;
}
.risk-card-footer {
    padding-top: 1rem;
    border-top: 1px solid #f1f5f9;
    margin-top: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
</style>
';

include 'includes/header.php';
?>

<div class="filter-tabs animate-fade-in">
    <a href="titik-risiko.php" class="filter-tab <?= $filterLevel === '' ? 'active' : '' ?>">Semua Titik</a>
    <a href="titik-risiko.php?level=tinggi" class="filter-tab <?= $filterLevel === 'tinggi' ? 'active' : '' ?>" style="<?= $filterLevel === 'tinggi' ? 'background: var(--risk-high-text); color: white; border-color: var(--risk-high-text);' : '' ?>">
        <i class="ph-fill ph-warning-octagon"></i> Risiko Tinggi
    </a>
    <a href="titik-risiko.php?level=sedang" class="filter-tab <?= $filterLevel === 'sedang' ? 'active' : '' ?>" style="<?= $filterLevel === 'sedang' ? 'background: #ca8a04; color: white; border-color: #ca8a04;' : '' ?>">
        <i class="ph-fill ph-warning"></i> Risiko Sedang
    </a>
    <a href="titik-risiko.php?level=rendah" class="filter-tab <?= $filterLevel === 'rendah' ? 'active' : '' ?>" style="<?= $filterLevel === 'rendah' ? 'background: var(--risk-low-text); color: white; border-color: var(--risk-low-text);' : '' ?>">
        <i class="ph-fill ph-shield-check"></i> Risiko Rendah
    </a>
</div>

<?php if (!$apiResponse['success']): ?>
    <div class="glass-card animate-fade-in" style="background: #fef2f2; border-color: #fecaca; margin-bottom: 2rem;">
        <h3 style="color: #991b1b;"><i class="ph-bold ph-warning-circle"></i> Gagal Memuat Data</h3>
        <p style="color: #b91c1c;"><?= htmlspecialchars($apiResponse['error']) ?></p>
    </div>
<?php elseif (empty($dataTitik)): ?>
    <div class="glass-card animate-fade-in text-center" style="padding: 4rem 2rem; text-align: center;">
        <i class="ph-thin ph-map-pin-line" style="font-size: 4rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
        <h3 style="color: var(--text-main);">Tidak Ada Data</h3>
        <p style="color: var(--text-muted);">Belum ada titik risiko yang tercatat dengan kriteria tersebut.</p>
    </div>
<?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;" class="animate-fade-in delay-1">
        <?php foreach ($dataTitik as $t): ?>
            <?php
                $lvl = strtolower($t['level_risiko_awal'] ?? 'rendah');
            ?>
            <div class="glass-card risk-card">
                <div class="risk-card-body">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <span class="badge badge-<?= $lvl ?>"><i class="ph-fill ph-circle" style="margin-right: 4px; font-size: 0.5rem;"></i> <?= ucfirst($lvl) ?></span>
                        <?php if(!empty($t['status_aktif'])): ?>
                            <span style="font-size: 0.75rem; color: #16a34a; font-weight: 600;"><i class="ph-fill ph-activity"></i> Aktif</span>
                        <?php else: ?>
                            <span style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;"><i class="ph-bold ph-check-square-offset"></i> Selesai</span>
                        <?php endif; ?>
                    </div>
                    
                    <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem; line-height: 1.3;"><?= htmlspecialchars($t['nama_titik'] ?? 'Tanpa Nama') ?></h3>
                    
                    <p style="color: var(--text-muted); font-size: 0.9rem; display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <i class="ph ph-map-pin" style="margin-top: 3px; flex-shrink: 0;"></i> 
                        <?= htmlspecialchars($t['alamat'] ?? '-') ?>
                    </p>
                    <p style="color: var(--text-muted); font-size: 0.9rem; display: flex; gap: 0.5rem;">
                        <i class="ph ph-warning-circle" style="margin-top: 3px; flex-shrink: 0;"></i> 
                        Risiko: <?= htmlspecialchars($t['jenis_risiko'] ?? '-') ?>
                    </p>
                </div>
                
                <div class="risk-card-footer">
                    <span style="font-size: 0.8rem; color: var(--text-muted);">ID: #<?= $t['id'] ?></span>
                    <a href="detail.php?id=<?= $t['id'] ?>" class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.8rem;">
                        Detail <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
