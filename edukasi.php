<?php
session_start();
require_once __DIR__ . '/includes/api.php';

$pageTitle = 'Edukasi & Artikel';
$pageSubtitle = 'Pencegahan Demam Berdarah Dengue';
$currentMenu = 'edukasi';

// Ambil data edukasi dari backend
$apiResponse = api_get('/edukasi');
$edukasiList = $apiResponse['success'] ? $apiResponse['data'] : [];

$extraHead = '
<style>
.edu-hero {
    background: linear-gradient(135deg, var(--green-mid), var(--green-dark));
    color: white;
    padding: 3rem;
    border-radius: var(--radius);
    text-align: center;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}
.edu-hero h2 { color: white; font-size: 2.5rem; margin-bottom: 1rem; }
.edu-hero p { font-size: 1.1rem; opacity: 0.9; max-width: 700px; margin: 0 auto; color: white; }
.edu-bg-icon {
    position: absolute;
    font-size: 15rem;
    color: rgba(255,255,255,0.05);
    top: -2rem;
    right: -2rem;
    pointer-events: none;
}
.grid-3m {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}
.card-3m {
    text-align: center;
    padding: 2.5rem 2rem;
}
.icon-wrapper {
    width: 80px;
    height: 80px;
    background: var(--green-bg);
    color: var(--green-dark);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    margin: 0 auto 1.5rem;
    transition: transform 0.3s ease;
}
.card-3m:hover .icon-wrapper {
    transform: scale(1.1);
    background: var(--green-mid);
    color: white;
}
.plus-section {
    background: var(--green-bg);
    border-radius: var(--radius);
    padding: 2rem;
    border: 1px dashed var(--green-mid);
}
.plus-list {
    list-style: none;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-top: 1.5rem;
}
.plus-list li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: white;
    padding: 1rem;
    border-radius: var(--radius-sm);
    box-shadow: var(--shadow);
    color: var(--text-dark);
    font-weight: 500;
}

/* Artikel Section */
.artikel-section {
    margin-top: 3rem;
}
.artikel-title {
    color: var(--green-dark);
    font-size: 1.75rem;
    margin-bottom: 1.5rem;
    text-align: center;
}
.artikel-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}
.artikel-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
}
.artikel-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,.1);
}
.artikel-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    background: #f0f0f0;
}
.artikel-content {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.artikel-kategori {
    font-size: 0.8rem;
    text-transform: uppercase;
    font-weight: 700;
    color: var(--green-mid);
    margin-bottom: 0.5rem;
    letter-spacing: 0.5px;
}
.artikel-judul {
    font-size: 1.2rem;
    color: var(--text-dark);
    margin-bottom: 1rem;
    line-height: 1.4;
}
.artikel-snippet {
    color: var(--text-muted);
    font-size: 0.95rem;
    margin-bottom: 1.5rem;
    line-height: 1.5;
    flex: 1;
}
.btn-baca {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--green-dark);
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s;
}
.btn-baca:hover {
    color: #0d4f4b;
}
</style>
';

include 'includes/header.php';
?>

<div class="edu-hero animate-fade-in">
    <i class="ph-fill ph-drop edu-bg-icon"></i>
    <h2>Apa itu 3M Plus?</h2>
    <p>3M Plus adalah tindakan pencegahan utama yang paling efektif untuk memberantas sarang nyamuk Aedes aegypti penyebar penyakit Demam Berdarah Dengue (DBD).</p>
</div>

<div class="grid-3m animate-fade-in delay-1">
    <div class="glass-card card-3m">
        <div class="icon-wrapper"><i class="ph-bold ph-drop"></i></div>
        <h3>Menguras</h3>
        <p style="color: var(--text-muted); margin-top: 0.5rem;">Membersihkan tempat yang sering dijadikan tempat penampungan air seperti bak mandi, ember air, tempat penampungan air minum, penampung air lemari es dan lain-lain.</p>
    </div>
    
    <div class="glass-card card-3m">
        <div class="icon-wrapper"><i class="ph-bold ph-trash"></i></div>
        <h3>Menutup</h3>
        <p style="color: var(--text-muted); margin-top: 0.5rem;">Menutup rapat-rapat tempat-tempat penampungan air seperti drum, kendi, toren air, dan lain sebagainya agar nyamuk tidak dapat bertelur di dalamnya.</p>
    </div>
    
    <div class="glass-card card-3m">
        <div class="icon-wrapper"><i class="ph-bold ph-recycle"></i></div>
        <h3>Mendaur Ulang</h3>
        <p style="color: var(--text-muted); margin-top: 0.5rem;">Memanfaatkan kembali atau mendaur ulang barang bekas yang memiliki potensi untuk jadi tempat perkembangbiakan nyamuk penular Demam Berdarah.</p>
    </div>
</div>

<div class="plus-section animate-fade-in delay-2">
    <div style="text-align: center;">
        <h3 style="color: var(--green-dark); font-size: 1.75rem;">Apa saja yang termasuk "Plus"?</h3>
        <p style="color: var(--green-dark); opacity: 0.8; margin-top: 0.5rem;">Segala bentuk kegiatan pencegahan tambahan selain 3M</p>
    </div>
    
    <ul class="plus-list">
        <li><i class="ph-fill ph-check-circle" style="color: var(--green-mid); font-size: 1.5rem;"></i> Memelihara ikan pemakan jentik</li>
        <li><i class="ph-fill ph-check-circle" style="color: var(--green-mid); font-size: 1.5rem;"></i> Menggunakan obat anti nyamuk</li>
        <li><i class="ph-fill ph-check-circle" style="color: var(--green-mid); font-size: 1.5rem;"></i> Memasang kawat kasa pada jendela</li>
        <li><i class="ph-fill ph-check-circle" style="color: var(--green-mid); font-size: 1.5rem;"></i> Gotong royong membersihkan lingkungan</li>
        <li><i class="ph-fill ph-check-circle" style="color: var(--green-mid); font-size: 1.5rem;"></i> Memeriksa tempat penampungan air</li>
        <li><i class="ph-fill ph-check-circle" style="color: var(--green-mid); font-size: 1.5rem;"></i> Menanam tanaman pengusir nyamuk</li>
    </ul>
</div>

<?php if (count($edukasiList) > 0): ?>
<div class="artikel-section animate-fade-in delay-2">
    <h3 class="artikel-title">Artikel Edukasi Terbaru</h3>
    <div class="artikel-grid">
        <?php foreach ($edukasiList as $artikel): ?>
            <?php 
                $imgUrl = $artikel['gambar'] 
                    ? str_replace('/api', '/storage/', API_BASE_URL) . $artikel['gambar'] 
                    : 'assets/img/hero_house.png'; // fallback image
                $snippet = mb_strimwidth(strip_tags($artikel['konten']), 0, 100, '...');
            ?>
            <div class="artikel-card">
                <img src="<?= htmlspecialchars($imgUrl) ?>" alt="<?= htmlspecialchars($artikel['judul']) ?>" class="artikel-img">
                <div class="artikel-content">
                    <div class="artikel-kategori"><?= htmlspecialchars($artikel['kategori']) ?></div>
                    <h4 class="artikel-judul"><?= htmlspecialchars($artikel['judul']) ?></h4>
                    <p class="artikel-snippet"><?= htmlspecialchars($snippet) ?></p>
                    <a href="edukasi-detail.php?id=<?= $artikel['id'] ?>" class="btn-baca">
                        Baca Selengkapnya <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
