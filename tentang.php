<?php
/**
 * WaspadaDBD - Tentang Aplikasi (tentang.php)
 * Halaman informasi tentang aplikasi WaspadaDBD
 */

$pageTitle   = 'Tentang Aplikasi';
$breadcrumbs = [['label' => 'Tentang Aplikasi']];

include __DIR__ . '/includes/header.php';
?>

<main class="page-content">

    <!-- Hero -->
    <div class="welcome-banner fade-in-up" style="margin-bottom: 28px;">
        <i class="bi bi-info-circle-fill welcome-banner-icon"></i>
        <div>
            <h2>Tentang WaspadaDBD</h2>
            <p>Sistem Monitoring dan Pemetaan Risiko Demam Berdarah Dengue untuk masyarakat Indonesia.</p>
        </div>
        <div class="welcome-meta">
            <span><i class="bi bi-code-slash"></i> PHP Native</span>
            <span><i class="bi bi-bootstrap"></i> Bootstrap 5</span>
            <span><i class="bi bi-geo-alt"></i> Leaflet.js</span>
        </div>
    </div>

    <div class="row g-4">
        <!-- Deskripsi -->
        <div class="col-lg-8 fade-in-up delay-1">
            <div class="content-card">
                <div class="content-card-header">
                    <h6 class="card-title"><i class="bi bi-app-indicator"></i> Deskripsi Aplikasi</h6>
                </div>
                <div class="content-card-body">
                    <p style="font-size: 14px; color: #374151; line-height: 1.8; margin-bottom: 14px;">
                        <strong>WaspadaDBD</strong> adalah platform monitoring berbasis web yang dirancang untuk membantu
                        masyarakat dan petugas kesehatan dalam memantau, melaporkan, dan memetakan titik-titik risiko
                        Demam Berdarah Dengue (DBD) secara real-time.
                    </p>
                    <p style="font-size: 14px; color: #374151; line-height: 1.8; margin-bottom: 0;">
                        Aplikasi ini terhubung langsung dengan backend Laravel API yang menyediakan data titik risiko,
                        riwayat pemeriksaan, dan informasi wilayah. Dengan tampilan peta interaktif dan dashboard
                        statistik, WaspadaDBD memudahkan proses pemantauan dan pengambilan keputusan dalam
                        penanganan DBD.
                    </p>
                </div>
            </div>
        </div>

        <!-- Teknologi -->
        <div class="col-lg-4 fade-in-up delay-2">
            <div class="content-card">
                <div class="content-card-header">
                    <h6 class="card-title"><i class="bi bi-cpu"></i> Teknologi</h6>
                </div>
                <div class="content-card-body" style="padding: 12px 18px;">
                    <?php
                        $techs = [
                            ['icon' => 'bi-filetype-php', 'name' => 'PHP 8+ Native', 'color' => '#7c3aed', 'bg' => '#f3e8ff'],
                            ['icon' => 'bi-bootstrap', 'name' => 'Bootstrap 5', 'color' => '#7c3aed', 'bg' => '#f3e8ff'],
                            ['icon' => 'bi-geo-alt', 'name' => 'Leaflet.js', 'color' => '#198754', 'bg' => '#d1e7dd'],
                            ['icon' => 'bi-filetype-js', 'name' => 'JavaScript ES6+', 'color' => '#d97706', 'bg' => '#fff3cd'],
                            ['icon' => 'bi-filetype-css', 'name' => 'CSS3 Custom', 'color' => '#0891b2', 'bg' => '#cff4fc'],
                            ['icon' => 'bi-cloud-arrow-up', 'name' => 'Laravel API', 'color' => '#dc3545', 'bg' => '#f8d7da'],
                        ];
                        foreach ($techs as $t):
                    ?>
                    <div style="display: flex; align-items: center; gap: 10px; padding: 9px 0; border-bottom: 1px solid #f1f5f9;">
                        <div style="width: 32px; height: 32px; background: <?= $t['bg'] ?>; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: <?= $t['color'] ?>; font-size: 15px; flex-shrink: 0;">
                            <i class="bi <?= $t['icon'] ?>"></i>
                        </div>
                        <span style="font-size: 13px; font-weight: 600; color: #1e293b;"><?= $t['name'] ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Fitur Utama -->
    <div class="content-card mt-4 fade-in-up delay-3">
        <div class="content-card-header">
            <h6 class="card-title"><i class="bi bi-stars"></i> Fitur Utama</h6>
        </div>
        <div class="content-card-body">
            <div class="row g-3">
                <?php
                    $features = [
                        ['icon' => 'bi-speedometer2', 'title' => 'Dashboard Statistik', 'desc' => 'Pantau jumlah titik risiko berdasarkan level secara real-time'],
                        ['icon' => 'bi-map-fill', 'title' => 'Peta Interaktif', 'desc' => 'Visualisasi sebaran titik risiko dengan Leaflet.js dan OpenStreetMap'],
                        ['icon' => 'bi-table', 'title' => 'Tabel Data Lengkap', 'desc' => 'Daftar titik risiko dengan filter dan pencarian client-side'],
                        ['icon' => 'bi-search', 'title' => 'Pencarian Lokasi', 'desc' => 'Cari titik risiko berdasarkan nama, wilayah, atau level'],
                        ['icon' => 'bi-book-fill', 'title' => 'Edukasi DBD', 'desc' => 'Informasi lengkap tentang gejala, pencegahan, dan cara memberantas sarang nyamuk'],
                        ['icon' => 'bi-phone-fill', 'title' => 'Responsif Mobile', 'desc' => 'Tampilan optimal di semua ukuran layar dengan sidebar offcanvas'],
                    ];
                    foreach ($features as $f):
                ?>
                <div class="col-md-6 col-lg-4">
                    <div style="display: flex; align-items: flex-start; gap: 12px; padding: 14px; background: #fafbfd; border: 1px solid #e8edf2; border-radius: 10px;">
                        <div style="width: 40px; height: 40px; background: #d1e7dd; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #198754; font-size: 18px; flex-shrink: 0;">
                            <i class="bi <?= $f['icon'] ?>"></i>
                        </div>
                        <div>
                            <div style="font-size: 13.5px; font-weight: 700; color: #1e293b; margin-bottom: 3px;"><?= $f['title'] ?></div>
                            <div style="font-size: 12.5px; color: #64748b; line-height: 1.5;"><?= $f['desc'] ?></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Info API -->
    <div class="row g-4 mt-0">
        <div class="col-12 fade-in-up delay-4">
            <div class="content-card" style="border: 1.5px solid #d1e7dd;">
                <div class="content-card-header" style="background: #f0faf5;">
                    <h6 class="card-title"><i class="bi bi-cloud-check-fill" style="color: #198754;"></i> Konfigurasi API</h6>
                </div>
                <div class="content-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="detail-info-item">
                                <div class="detail-info-icon"><i class="bi bi-server"></i></div>
                                <div class="detail-info-content">
                                    <div class="detail-info-label">Backend API URL</div>
                                    <div class="detail-info-value" style="font-family: monospace; font-size: 13px;">http://localhost:8000/api</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-info-item">
                                <div class="detail-info-icon"><i class="bi bi-globe"></i></div>
                                <div class="detail-info-content">
                                    <div class="detail-info-label">Frontend URL</div>
                                    <div class="detail-info-value" style="font-family: monospace; font-size: 13px;">http://waspadadbd-frontend.test</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-info-item">
                                <div class="detail-info-icon"><i class="bi bi-code-square"></i></div>
                                <div class="detail-info-content">
                                    <div class="detail-info-label">Endpoint Titik Risiko</div>
                                    <div class="detail-info-value" style="font-family: monospace; font-size: 13px;">GET /api/titik-risiko</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-info-item">
                                <div class="detail-info-icon"><i class="bi bi-clipboard2-pulse"></i></div>
                                <div class="detail-info-content">
                                    <div class="detail-info-label">Endpoint Pemeriksaan</div>
                                    <div class="detail-info-value" style="font-family: monospace; font-size: 13px;">GET /api/titik-risiko/{id}/pemeriksaan</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
