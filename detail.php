<?php
session_start();
require_once __DIR__ . '/includes/api.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: titik-risiko.php');
    exit;
}

// Fetch Detail
$detailResp = api_get("/titik-risiko/$id");
$detail = $detailResp['success'] ? $detailResp['data'] : null;

// Fetch History
$historyResp = api_get("/titik-risiko/$id/pemeriksaan");
$history = $historyResp['success'] && is_array($historyResp['data']) ? $historyResp['data'] : [];

$namaTitik = $detail['nama_titik'] ?? 'Detail Titik Risiko';

$pageTitle = 'Detail Lokasi';
$pageSubtitle = $namaTitik;
$currentMenu = 'titik';

$extraHead = '
<style>
.detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 2rem;
}
@media (max-width: 1024px) {
    .detail-grid { grid-template-columns: 1fr; }
}
.info-row {
    display: flex;
    padding: 1rem 0;
    border-bottom: 1px dashed #e2e8f0;
}
.info-row:last-child { border-bottom: none; }
.info-label {
    width: 140px;
    font-weight: 600;
    color: var(--text-muted);
    flex-shrink: 0;
}
.info-value {
    flex: 1;
    color: var(--text-main);
    font-weight: 500;
}
.history-table {
    width: 100%;
    border-collapse: collapse;
}
.history-table th {
    background: #f8fafc;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: var(--text-muted);
    font-size: 0.875rem;
    border-bottom: 2px solid #e2e8f0;
}
.history-table td {
    padding: 1rem;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.9rem;
}
.history-table tr:hover td {
    background: #f8fafc;
}
</style>
';

// Prepare Map
$lat = $detail['latitude'] ?? null;
$lng = $detail['longitude'] ?? null;
$lvl = strtolower($detail['level_risiko_awal'] ?? 'rendah');

$extraScripts = '';
if ($lat && $lng) {
    $extraScripts = '
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const map = L.map("detailMap").setView(['.$lat.', '.$lng.'], 15);
            L.tileLayer("https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png", {
                attribution: "&copy; OpenStreetMap &copy; CARTO"
            }).addTo(map);

            const colors = { "rendah": "#22c55e", "sedang": "#eab308", "tinggi": "#ef4444" };
            const color = colors["'.$lvl.'"] || colors["rendah"];
            
            const markerHtml = `<div style="background-color: ${color}; width: 30px; height: 30px; border-radius: 50%; border: 4px solid white; box-shadow: 0 3px 6px rgba(0,0,0,0.3);"></div>`;
            
            const customIcon = L.divIcon({
                className: "custom-div-icon",
                html: markerHtml,
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });

            L.marker(['.$lat.', '.$lng.'], {icon: customIcon})
                .addTo(map)
                .bindPopup("<b>'.$namaTitik.'</b><br>Risiko: '.ucfirst($lvl).'").openPopup();
        });
    </script>
    ';
}

include 'includes/header.php';
?>

<?php if (!$detail): ?>
    <div class="glass-card animate-fade-in text-center" style="padding: 4rem 2rem;">
        <i class="ph-thin ph-file-x" style="font-size: 4rem; color: #ef4444; margin-bottom: 1rem;"></i>
        <h3 style="color: var(--text-main);">Data Tidak Ditemukan</h3>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Titik risiko dengan ID #<?= $id ?> tidak ditemukan atau terjadi kesalahan server.</p>
        <a href="titik-risiko.php" class="btn btn-primary"><i class="ph-bold ph-arrow-left"></i> Kembali ke Daftar</a>
    </div>
<?php else: ?>

    <div style="margin-bottom: 1.5rem;" class="animate-fade-in">
        <a href="titik-risiko.php" class="btn" style="background: white; border: 1px solid #e2e8f0; color: var(--text-muted);"><i class="ph-bold ph-arrow-left"></i> Kembali</a>
    </div>

    <div class="detail-grid animate-fade-in delay-1">
        <!-- Info Card -->
        <div class="glass-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.25rem;"><i class="ph-fill ph-info" style="color: var(--primary-color);"></i> Informasi Lokasi</h3>
                <span class="badge badge-<?= $lvl ?>"><?= ucfirst($lvl) ?></span>
            </div>
            
            <div class="info-row">
                <div class="info-label">Nama Lokasi</div>
                <div class="info-value"><?= htmlspecialchars($detail['nama_titik'] ?? '-') ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Alamat Lengkap</div>
                <div class="info-value"><?= htmlspecialchars($detail['alamat'] ?? '-') ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Koordinat GPS</div>
                <div class="info-value" style="font-family: monospace; color: var(--primary-color);">
                    <?= $lat && $lng ? "$lat, $lng" : 'Belum Tersedia' ?>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Jenis Risiko</div>
                <div class="info-value"><?= htmlspecialchars($detail['jenis_risiko'] ?? '-') ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Status</div>
                <div class="info-value">
                    <?php if(!empty($detail['status_aktif'])): ?>
                        <span style="color: #16a34a; font-weight: 700;"><i class="ph-bold ph-activity"></i> Aktif dipantau</span>
                    <?php else: ?>
                        <span style="color: #64748b; font-weight: 700;"><i class="ph-bold ph-check"></i> Selesai (Aman)</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div style="margin-top: 2rem;">
                <p style="font-size: 0.85rem; color: var(--text-muted); text-align: center; font-style: italic;">
                    <i class="ph-fill ph-info"></i> Data ini dipantau secara berkala oleh Dinas Kesehatan.
                </p>
            </div>
        </div>

        <!-- Map Card -->
        <div class="glass-card" style="padding: 0; display: flex; flex-direction: column;">
            <div style="padding: 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1.25rem;"><i class="ph-fill ph-map-pin" style="color: var(--primary-color);"></i> Titik Peta</h3>
                <?php if ($lat && $lng): ?>
                    <a href="https://www.google.com/maps?q=<?= $lat ?>,<?= $lng ?>" target="_blank" class="badge" style="background: #e0f2fe; color: #0284c7; text-decoration: none;">Buka di Google Maps <i class="ph-bold ph-arrow-square-out"></i></a>
                <?php endif; ?>
            </div>
            <?php if ($lat && $lng): ?>
                <div id="detailMap" style="flex: 1; min-height: 300px; z-index: 1;"></div>
            <?php else: ?>
                <div style="flex: 1; min-height: 300px; display: flex; align-items: center; justify-content: center; flex-direction: column; color: var(--text-muted); background: #f8fafc;">
                    <i class="ph-thin ph-map-trifold" style="font-size: 3rem; margin-bottom: 0.5rem;"></i>
                    <p>Koordinat peta tidak tersedia.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- History Card -->
    <div class="glass-card animate-fade-in delay-2">
        <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem;"><i class="ph-fill ph-clock-counter-clockwise" style="color: var(--primary-color);"></i> Riwayat Pemeriksaan</h3>
        
        <?php if (empty($history)): ?>
            <div style="text-align: center; padding: 2rem; color: var(--text-muted); border: 1px dashed #cbd5e1; border-radius: var(--radius-md);">
                <i class="ph-thin ph-clipboard" style="font-size: 2.5rem; margin-bottom: 0.5rem;"></i>
                <p>Belum ada catatan pemeriksaan untuk lokasi ini.</p>
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Petugas</th>
                            <th>Hasil & Kondisi</th>
                            <th>Tindakan</th>
                            <th>Status Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $h): ?>
                            <tr>
                                <td style="white-space: nowrap;">
                                    <div style="font-weight: 600; color: var(--text-main);"><?= date('d M Y', strtotime($h['tanggal_pemeriksaan'] ?? $h['tanggal'] ?? 'today')) ?></div>
                                    <?php if(isset($h['tanggal_pemeriksaan'])): ?>
                                        <div style="font-size: 0.8rem; color: var(--text-muted);"><?= date('H:i', strtotime($h['tanggal_pemeriksaan'])) ?> WIB</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <div style="width: 28px; height: 28px; border-radius: 50%; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">
                                            <?= strtoupper(substr($h['petugas_id'] ?? 'P', 0, 1)) ?>
                                        </div>
                                        <span>Petugas #<?= htmlspecialchars($h['petugas_id'] ?? '-') ?></span>
                                    </div>
                                </td>
                                <td>
                                    <?php if(isset($h['ditemukan_jentik']) && $h['ditemukan_jentik']): ?>
                                        <span class="badge badge-tinggi" style="margin-bottom: 4px;">Ada Jentik</span><br>
                                    <?php else: ?>
                                        <span class="badge badge-rendah" style="margin-bottom: 4px;">Bebas Jentik</span><br>
                                    <?php endif; ?>
                                    <span style="font-size: 0.85rem; color: var(--text-muted);"><?= htmlspecialchars($h['kondisi_lingkungan'] ?? '-') ?></span>
                                </td>
                                <td><?= htmlspecialchars($h['tindakan_dilakukan'] ?? '-') ?></td>
                                <td>
                                    <?php
                                        $s = strtolower($h['status_akhir'] ?? 'aman');
                                        if ($s === 'perlu tindakan' || $s === 'bahaya') echo '<span class="badge badge-tinggi">Perlu Tindakan</span>';
                                        elseif ($s === 'perlu pemantauan' || $s === 'waspada') echo '<span class="badge badge-sedang">Pantau</span>';
                                        else echo '<span class="badge badge-rendah">Aman</span>';
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>
