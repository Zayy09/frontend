<?php
/**
 * WaspadaDBD - Sidebar Navigasi
 * Sidebar kiri dengan menu utama aplikasi
 */

// Tentukan halaman aktif
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- =====================================================
     SIDEBAR KIRI
     ===================================================== -->
<aside id="sidebar">

    <!-- Brand / Logo -->
    <a class="sidebar-brand" href="/index.php">
        <div class="sidebar-brand-icon">
            <i class="bi bi-shield-fill-check"></i>
        </div>
        <div class="sidebar-brand-text">
            <span class="brand-title">WaspadaDBD</span>
            <span class="brand-subtitle">Monitoring DBD</span>
        </div>
    </a>

    <!-- Navigasi Utama -->
    <nav class="sidebar-nav">

        <!-- Label: Menu Utama -->
        <div class="sidebar-nav-label">Menu Utama</div>

        <ul class="nav flex-column">
            <!-- Beranda -->
            <li class="nav-item">
                <a class="nav-link <?= ($currentPage === 'index.php') ? 'active' : '' ?>"
                   href="/index.php">
                    <i class="bi bi-house-door-fill nav-icon"></i>
                    <span class="nav-text">Beranda</span>
                </a>
            </li>

            <!-- Titik Risiko -->
            <li class="nav-item">
                <a class="nav-link <?= ($currentPage === 'titik-risiko.php' || $currentPage === 'detail.php') ? 'active' : '' ?>"
                   href="/titik-risiko.php">
                    <i class="bi bi-geo-alt-fill nav-icon"></i>
                    <span class="nav-text">Titik Risiko</span>
                </a>
            </li>

            <!-- Pencarian Lokasi -->
            <li class="nav-item">
                <a class="nav-link <?= ($currentPage === 'search.php') ? 'active' : '' ?>"
                   href="/search.php">
                    <i class="bi bi-search nav-icon"></i>
                    <span class="nav-text">Pencarian Lokasi</span>
                </a>
            </li>
        </ul>

        <!-- Label: Informasi -->
        <div class="sidebar-nav-label">Informasi</div>

        <ul class="nav flex-column">
            <!-- Edukasi DBD -->
            <li class="nav-item">
                <a class="nav-link <?= ($currentPage === 'edukasi.php') ? 'active' : '' ?>"
                   href="/edukasi.php">
                    <i class="bi bi-book-fill nav-icon"></i>
                    <span class="nav-text">Edukasi DBD</span>
                </a>
            </li>

            <!-- Tentang Aplikasi -->
            <li class="nav-item">
                <a class="nav-link <?= ($currentPage === 'tentang.php') ? 'active' : '' ?>"
                   href="/tentang.php">
                    <i class="bi bi-info-circle-fill nav-icon"></i>
                    <span class="nav-text">Tentang Aplikasi</span>
                </a>
            </li>
        </ul>

        <!-- Label: Info Cepat -->
        <div class="sidebar-nav-label">Info Cepat</div>

        <!-- Card Info Risiko di Sidebar -->
        <div style="
            background: rgba(255,255,255,0.10);
            border-radius: 10px;
            padding: 12px 14px;
            margin: 4px 0;
            border: 1px solid rgba(255,255,255,0.12);
        ">
            <div style="font-size: 11px; color: rgba(255,255,255,0.6); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">
                Level Risiko
            </div>
            <div style="display: flex; flex-direction: column; gap: 6px;">
                <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: rgba(255,255,255,0.85);">
                    <span style="width: 8px; height: 8px; background: #4ade80; border-radius: 50%; flex-shrink: 0;"></span>
                    Rendah — Aman terkendali
                </div>
                <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: rgba(255,255,255,0.85);">
                    <span style="width: 8px; height: 8px; background: #fbbf24; border-radius: 50%; flex-shrink: 0;"></span>
                    Sedang — Waspadai
                </div>
                <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: rgba(255,255,255,0.85);">
                    <span style="width: 8px; height: 8px; background: #f87171; border-radius: 50%; flex-shrink: 0;"></span>
                    Tinggi — Bahaya
                </div>
            </div>
        </div>

    </nav>
    <!-- /sidebar-nav -->

    <!-- Footer Sidebar: Info Pengguna -->
    <div class="sidebar-footer">
        <div class="sidebar-user-info">
            <div class="sidebar-user-avatar">
                <i class="bi bi-person-fill"></i>
            </div>
            <div class="sidebar-user-details">
                <div class="sidebar-user-name">Pengguna Umum</div>
                <div class="sidebar-user-role">Warga Terdaftar</div>
            </div>
            <i class="bi bi-three-dots-vertical" style="color: rgba(255,255,255,0.5); font-size: 14px;"></i>
        </div>

        <!-- Tahun & Versi -->
        <div style="text-align: center; margin-top: 10px; font-size: 10.5px; color: rgba(255,255,255,0.35);">
            WaspadaDBD v1.0 &copy; <?= date('Y') ?>
        </div>
    </div>

</aside>
<!-- /SIDEBAR -->
