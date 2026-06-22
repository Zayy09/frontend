<?php
session_start();
require_once __DIR__ . '/includes/api.php';

$pageTitle = 'Pencarian Lokasi';
$pageSubtitle = 'Cari titik risiko demam berdarah di sekitar Anda';
$currentMenu = 'search';

// Fetch all data for client-side search
$apiResponse = api_get('/titik-risiko');
$dataTitik = $apiResponse['success'] ? $apiResponse['data'] : [];

$extraHead = '
<style>
.search-container {
    max-width: 800px;
    margin: 0 auto 2rem;
    position: relative;
}
.search-input {
    width: 100%;
    padding: 1rem 1.5rem 1rem 3.5rem;
    font-size: 1.1rem;
    border: 2px solid #e2e8f0;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    transition: all 0.3s ease;
    font-family: \'Inter\', sans-serif;
}
.search-input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.15);
}
.search-icon {
    position: absolute;
    left: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.5rem;
    color: var(--text-muted);
}
.result-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    margin-bottom: 1rem;
    border: 1px solid #f1f5f9;
    transition: transform 0.2s, box-shadow 0.2s;
    text-decoration: none;
    color: inherit;
}
.result-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}
</style>
';

$extraScripts = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchInput");
    const resultsContainer = document.getElementById("resultsContainer");
    const noResultMsg = document.getElementById("noResultMsg");
    
    const allData = ' . json_encode($dataTitik) . ';
    
    function renderResults(query) {
        resultsContainer.innerHTML = "";
        
        if (!query) {
            noResultMsg.style.display = "none";
            return;
        }
        
        const q = query.toLowerCase();
        const filtered = allData.filter(item => {
            const nama = (item.nama_titik || "").toLowerCase();
            const alamat = (item.alamat || "").toLowerCase();
            const wilayah = (item.wilayah || "").toLowerCase();
            return nama.includes(q) || alamat.includes(q) || wilayah.includes(q);
        });
        
        if (filtered.length === 0) {
            noResultMsg.style.display = "block";
            return;
        }
        
        noResultMsg.style.display = "none";
        
        filtered.forEach(item => {
            const lvl = (item.level_risiko_awal || "rendah").toLowerCase();
            let badgeClass = "rendah";
            if(lvl === "sedang") badgeClass = "sedang";
            if(lvl === "tinggi") badgeClass = "tinggi";
            
            const cardHtml = `
                <a href="detail.php?id=${item.id}" class="result-card animate-fade-in">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--primary-light); color: var(--primary-dark); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                        <i class="ph-fill ph-map-pin"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <h4 style="font-size: 1.1rem; margin-bottom: 0.25rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${item.nama_titik || "Tanpa Nama"}</h4>
                        <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${item.alamat || "-"}</p>
                    </div>
                    <div>
                        <span class="badge badge-${badgeClass}">${lvl.charAt(0).toUpperCase() + lvl.slice(1)}</span>
                    </div>
                    <div style="color: var(--primary-color);">
                        <i class="ph-bold ph-caret-right"></i>
                    </div>
                </a>
            `;
            resultsContainer.insertAdjacentHTML("beforeend", cardHtml);
        });
    }

    searchInput.addEventListener("input", function(e) {
        renderResults(e.target.value.trim());
    });
});
</script>
';

include 'includes/header.php';
?>

<div class="search-container animate-fade-in">
    <i class="ph-bold ph-magnifying-glass search-icon"></i>
    <input type="text" id="searchInput" class="search-input" placeholder="Ketik nama lokasi, wilayah, atau alamat..." autocomplete="off">
</div>

<div style="max-width: 800px; margin: 0 auto;">
    <div id="noResultMsg" style="display: none; text-align: center; padding: 3rem; color: var(--text-muted);" class="glass-card">
        <i class="ph-thin ph-magnifying-glass-minus" style="font-size: 4rem; margin-bottom: 1rem;"></i>
        <h3>Tidak ditemukan</h3>
        <p>Lokasi yang Anda cari tidak ada dalam database kami.</p>
    </div>
    
    <div id="resultsContainer">
        <!-- JS akan me-render hasil di sini -->
    </div>
    
    <div class="glass-card animate-fade-in delay-1" style="text-align: center; margin-top: 2rem; background: var(--primary-light); border-color: #a7f3d0; box-shadow: none;">
        <i class="ph-fill ph-lightbulb" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 0.5rem;"></i>
        <h4 style="color: var(--primary-dark); margin-bottom: 0.5rem;">Ketahui Kondisi Lingkungan Anda</h4>
        <p style="color: var(--primary-dark); font-size: 0.9rem; opacity: 0.8;">Cari nama perumahan, jalan, atau fasilitas umum untuk melihat apakah ada laporan sarang nyamuk di sekitarnya.</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
