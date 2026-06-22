<?php
session_start();
require_once __DIR__ . '/includes/api.php';

$pageTitle = 'Peta Resiko DBD';
$currentMenu = 'titik';

// Ambil semua data titik risiko
$apiResponse = api_get('/titik-risiko');
$dataTitik = $apiResponse['success'] ? $apiResponse['data'] : [];

$extraHead = '
<style>
.map-page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 2rem;
}
.map-page-title h1 {
    font-size: 2.2rem;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}
.map-page-title p {
    font-size: 1.1rem;
    color: var(--text-main);
}
.map-header-img {
    height: 100px;
    width: auto;
}

.map-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    align-items: start;
}

@media (max-width: 1024px) {
    .map-layout {
        grid-template-columns: 1fr;
    }
    .map-header-img {
        display: none;
    }
}

.map-wrapper {
    background: white;
    padding: 1rem;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-sm);
    border: 1px solid rgba(0,0,0,0.05);
}

#mainMap {
    height: 600px;
    width: 100%;
    border-radius: var(--radius-lg);
    z-index: 1;
}

.legend-card {
    margin-bottom: 1.5rem;
    padding: 2rem;
    border-radius: var(--radius-xl);
}

.legend-title {
    text-align: center;
    font-size: 1.25rem;
    color: var(--text-main);
    margin-bottom: 1.5rem;
}

.legend-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.legend-dot {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    flex-shrink: 0;
}

.legend-text {
    line-height: 1.2;
}
.legend-text strong {
    font-size: 1.1rem;
    color: var(--text-main);
    font-family: \'Outfit\', sans-serif;
}
.legend-text small {
    color: var(--text-muted);
    font-size: 0.85rem;
}

.instruction-banner {
    background: var(--primary-light);
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    border-radius: var(--radius-lg);
    border: 1px solid #d1e7dd;
}

/* Kustomisasi Popup Leaflet */
.leaflet-popup-content-wrapper {
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.leaflet-popup-content {
    margin: 15px;
    text-align: center;
}
</style>
';

include 'includes/header.php';
?>

<div class="map-page-header animate-fade-in">
    <div class="map-page-title">
        <h1>Peta Resiko DBD</h1>
        <p>Lihat sebaran resiko DBD di wilayah Anda.</p>
    </div>
    <!-- Menggunakan gambar fallback jika ada, jika tidak kosongkan saja -->
    <img src="assets/img/hero_house.png" alt="Ilustrasi" class="map-header-img" onerror="this.style.display='none'">
</div>

<div class="map-layout animate-fade-in delay-1">
    <!-- Kolom Kiri: Peta -->
    <div class="map-wrapper">
        <div id="mainMap"></div>
    </div>

    <!-- Kolom Kanan: Legend & Info -->
    <div class="map-sidebar">
        
        <div class="glass-card legend-card">
            <h3 class="legend-title">Keterangan Resiko</h3>
            <ul class="legend-list">
                <li class="legend-item">
                    <span class="legend-dot" style="background: #22c55e;"></span>
                    <div class="legend-text">
                        <strong style="color: #166534;">Resiko Rendah</strong><br>
                        <small>Aman</small>
                    </div>
                </li>
                <li class="legend-item">
                    <span class="legend-dot" style="background: #eab308;"></span>
                    <div class="legend-text">
                        <strong style="color: #ca8a04;">Resiko Sedang</strong><br>
                        <small>Waspada</small>
                    </div>
                </li>
                <li class="legend-item">
                    <span class="legend-dot" style="background: #ef4444;"></span>
                    <div class="legend-text">
                        <strong style="color: #dc2626;">Resiko Tinggi</strong><br>
                        <small>Bahaya</small>
                    </div>
                </li>
            </ul>
        </div>

        <div class="instruction-banner">
            <i class="ph-bold ph-info" style="font-size: 2.5rem; color: var(--primary-color);"></i>
            <p style="font-size: 0.95rem; font-weight: 600; color: var(--primary-dark); margin: 0; line-height: 1.4;">
                Klik pada titik peta untuk melihat detail informasi wilayah.
            </p>
        </div>

    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Inisialisasi Peta (Default ke Bandung atau kordinat rata-rata)
    const map = L.map("mainMap").setView([-6.914744, 107.609810], 12); 
    L.tileLayer("https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png", {
        attribution: "&copy; OpenStreetMap &copy; CARTO"
    }).addTo(map);

    const titikData = <?= json_encode($dataTitik) ?>;
    const colors = { "rendah": "#22c55e", "sedang": "#eab308", "tinggi": "#ef4444" };

    const validMarkers = [];

    titikData.forEach(t => {
        if(t.latitude && t.longitude) {
            let lvl = (t.level_risiko_awal || 'rendah').toLowerCase();
            let color = colors[lvl] || "#64748b";
            
            let markerHtml = `<div style="background-color: ${color}; width: 28px; height: 28px; border-radius: 50%; border: 4px solid white; box-shadow: 0 4px 8px rgba(0,0,0,0.3);"></div>`;
            
            let customIcon = L.divIcon({
                className: "custom-div-icon",
                html: markerHtml,
                iconSize: [28, 28],
                iconAnchor: [14, 14]
            });
            
            let badgeBg = lvl === 'tinggi' ? '#fee2e2' : (lvl === 'sedang' ? '#fef9c3' : '#dcfce7');
            let badgeColor = lvl === 'tinggi' ? '#dc2626' : (lvl === 'sedang' ? '#ca8a04' : '#166534');
            let txtResiko = lvl.charAt(0).toUpperCase() + lvl.slice(1);

            let popupContent = `
                <div style="min-width: 180px; padding: 5px;">
                    ${lvl === 'tinggi' ? '<div style="text-align:center; margin-bottom:5px;"><i class="ph-fill ph-warning" style="color:#ef4444; font-size:2rem;"></i></div>' : ''}
                    <h4 style="margin: 0 0 5px 0; font-family: 'Outfit', sans-serif; font-size:1.1rem; color:#0f172a;">${t.nama_titik}</h4>
                    <div style="display:inline-block; padding: 3px 8px; background:${badgeBg}; color:${badgeColor}; border-radius:20px; font-size:0.75rem; font-weight:700; margin-bottom: 12px;">
                        Resiko ${txtResiko}
                    </div>
                    <div style="text-align:center;">
                        <a href="detail.php?id=${t.id}" style="display: inline-block; width: 100%; padding: 8px 0; background: transparent; color: #0f172a; border: 1px solid #cbd5e1; border-radius: 20px; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: background 0.2s;">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            `;
            
            let marker = L.marker([t.latitude, t.longitude], {icon: customIcon})
                .addTo(map)
                .bindPopup(popupContent);
            
            validMarkers.push([t.latitude, t.longitude]);
        }
    });

    // Auto fit map agar semua titik terlihat
    if (validMarkers.length > 0) {
        let bounds = L.latLngBounds(validMarkers);
        map.fitBounds(bounds, {padding: [50, 50]});
    }
});
</script>

<?php include 'includes/footer.php'; ?>
