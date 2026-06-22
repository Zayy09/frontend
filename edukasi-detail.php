<?php
session_start();
require_once __DIR__ . '/includes/api.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: edukasi.php');
    exit;
}

// Fetch Detail
$detailResp = api_get("/edukasi/$id");
$detail = $detailResp['success'] ? $detailResp['data'] : null;

$pageTitle = 'Detail Edukasi';
$pageSubtitle = $detail['judul'] ?? 'Artikel Tidak Ditemukan';
$currentMenu = 'edukasi';

$extraHead = '
<style>
.artikel-detail-container {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}
.artikel-header {
    padding: 2rem;
    border-bottom: 1px solid #f1f5f9;
}
.artikel-kategori-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #e0f2fe;
    color: var(--primary-dark);
    border-radius: 9999px;
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
}
.artikel-judul-utama {
    font-size: 2rem;
    color: var(--text-main);
    line-height: 1.3;
    margin-bottom: 1rem;
}
.artikel-meta {
    font-size: 0.9rem;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 1rem;
}
.artikel-hero-img {
    width: 100%;
    max-height: 400px;
    object-fit: cover;
    background: #f8fafc;
}
.artikel-konten {
    padding: 2rem;
    font-size: 1.05rem;
    color: var(--text-main);
    line-height: 1.8;
}
.artikel-konten p {
    margin-bottom: 1.5rem;
}
</style>
';

include 'includes/header.php';
?>

<div style="margin-bottom: 1.5rem;" class="animate-fade-in">
    <a href="edukasi.php" class="btn" style="background: white; border: 1px solid #e2e8f0; color: var(--text-muted);"><i class="ph-bold ph-arrow-left"></i> Kembali ke Daftar Artikel</a>
</div>

<?php if (!$detail): ?>
    <div class="glass-card animate-fade-in text-center" style="padding: 4rem 2rem;">
        <i class="ph-thin ph-file-x" style="font-size: 4rem; color: #ef4444; margin-bottom: 1rem;"></i>
        <h3 style="color: var(--text-main);">Artikel Tidak Ditemukan</h3>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Artikel dengan ID #<?= $id ?> tidak ditemukan atau terjadi kesalahan server.</p>
        <a href="edukasi.php" class="btn btn-primary"><i class="ph-bold ph-arrow-left"></i> Kembali ke Edukasi</a>
    </div>
<?php else: ?>
    <?php 
        $imgUrl = $detail['gambar'] 
            ? str_replace('/api', '/storage/', API_BASE_URL) . $detail['gambar'] 
            : null;
    ?>
    <div class="artikel-detail-container animate-fade-in delay-1">
        <div class="artikel-header">
            <span class="artikel-kategori-badge"><?= htmlspecialchars($detail['kategori']) ?></span>
            <h1 class="artikel-judul-utama"><?= htmlspecialchars($detail['judul']) ?></h1>
            <div class="artikel-meta">
                <span><i class="ph-fill ph-calendar-blank"></i> <?= date('d M Y', strtotime($detail['created_at'])) ?></span>
                <span><i class="ph-fill ph-user"></i> Penulis: Admin</span>
            </div>
        </div>
        
        <?php if ($imgUrl): ?>
            <img src="<?= htmlspecialchars($imgUrl) ?>" alt="<?= htmlspecialchars($detail['judul']) ?>" class="artikel-hero-img">
        <?php endif; ?>

        <div class="artikel-konten">
            <!-- Menampilkan konten dengan nl2br untuk mengganti newline dengan <br> jika bukan HTML -->
            <!-- Karena konten bisa mengandung paragraf dsb. -->
            <?= nl2br(htmlspecialchars($detail['konten'])) ?>
        </div>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
