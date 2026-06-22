/**
 * WaspadaDBD - JavaScript Utama
 * Menangani interaksi UI, API fetching, dan fungsi global
 */

// =====================================================
// KONFIGURASI API
// =====================================================
const API_BASE_URL = 'http://localhost:8000/api';

// =====================================================
// SIDEBAR TOGGLE
// =====================================================

document.addEventListener('DOMContentLoaded', function () {
    // Tombol toggle sidebar (desktop & tablet)
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('show');
        });
    }

    // Tutup sidebar saat klik di luar (mobile)
    document.addEventListener('click', function (e) {
        if (window.innerWidth <= 992) {
            if (sidebar && !sidebar.contains(e.target) && sidebarToggle && !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        }
    });

    // Set halaman aktif di sidebar
    setActiveSidebarItem();

    // Inisialisasi tooltips Bootstrap
    initTooltips();

    // Animasi masuk elemen
    initFadeAnimations();
});

// =====================================================
// AKTIFKAN ITEM SIDEBAR SESUAI HALAMAN
// =====================================================
function setActiveSidebarItem() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.php';
    const navLinks = document.querySelectorAll('.sidebar-nav .nav-link');

    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href) {
            const linkPage = href.split('/').pop();
            if (linkPage === currentPage ||
                (currentPage === '' && linkPage === 'index.php') ||
                (currentPage === 'detail.php' && linkPage === 'titik-risiko.php' && false)) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        }
    });
}

// =====================================================
// INIT TOOLTIPS BOOTSTRAP
// =====================================================
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            placement: 'bottom'
        });
    });
}

// =====================================================
// ANIMASI FADE IN
// =====================================================
function initFadeAnimations() {
    const elements = document.querySelectorAll('.fade-in-up');
    elements.forEach((el, index) => {
        el.style.opacity = '0';
        setTimeout(() => {
            el.style.opacity = '';
        }, index * 50);
    });
}

// =====================================================
// FETCH DARI API (dengan error handling)
// =====================================================

/**
 * Mengambil data dari Laravel API
 * @param {string} endpoint - Endpoint API (contoh: /titik-risiko)
 * @returns {Promise<object>} - Data JSON
 */
async function fetchAPI(endpoint) {
    try {
        const response = await fetch(`${API_BASE_URL}${endpoint}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        return { success: true, data: data };
    } catch (error) {
        console.error('API Error:', error);
        return { success: false, error: error.message };
    }
}

// =====================================================
// RENDER LOADING STATE
// =====================================================
function renderLoading(containerId, message = 'Memuat data...') {
    const container = document.getElementById(containerId);
    if (container) {
        container.innerHTML = `
            <div class="loading-state">
                <div class="spinner-wdb"></div>
                <p style="font-size: 13px; color: #64748b; margin: 0;">${message}</p>
            </div>
        `;
    }
}

// =====================================================
// RENDER ERROR STATE
// =====================================================
function renderError(containerId, message = 'Gagal memuat data dari server.') {
    const container = document.getElementById(containerId);
    if (container) {
        container.innerHTML = `
            <div class="error-state">
                <i class="bi bi-wifi-off" style="font-size: 42px; color: #e2e8f0;"></i>
                <h6 style="font-size: 14px; font-weight: 700; color: #64748b; margin: 6px 0 4px;">Koneksi Bermasalah</h6>
                <p style="font-size: 12.5px; color: #94a3b8; margin: 0;">${message}</p>
                <button onclick="location.reload()" class="btn-primary-wdb mt-3" style="padding: 8px 16px; font-size: 12.5px;">
                    <i class="bi bi-arrow-clockwise"></i> Coba Lagi
                </button>
            </div>
        `;
    }
}

// =====================================================
// RENDER EMPTY STATE
// =====================================================
function renderEmpty(containerId, title = 'Data Tidak Ditemukan', message = 'Belum ada data yang tersedia saat ini.') {
    const container = document.getElementById(containerId);
    if (container) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h5>${title}</h5>
                <p>${message}</p>
            </div>
        `;
    }
}

// =====================================================
// FORMAT LEVEL RISIKO → BADGE HTML
// =====================================================
function getRiskBadge(level) {
    if (!level) return '<span class="risk-badge">-</span>';
    const l = level.toLowerCase();
    if (l === 'rendah') return `<span class="risk-badge rendah"><i class="bi bi-circle-fill" style="font-size:6px;"></i>Rendah</span>`;
    if (l === 'sedang') return `<span class="risk-badge sedang"><i class="bi bi-circle-fill" style="font-size:6px;"></i>Sedang</span>`;
    if (l === 'tinggi') return `<span class="risk-badge tinggi"><i class="bi bi-circle-fill" style="font-size:6px;"></i>Tinggi</span>`;
    return `<span class="risk-badge">${level}</span>`;
}

// =====================================================
// FORMAT TANGGAL INDONESIA
// =====================================================
function formatDate(dateStr) {
    if (!dateStr) return '-';
    const date = new Date(dateStr);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
}

// =====================================================
// FORMAT JAM
// =====================================================
function formatDateTime(dateStr) {
    if (!dateStr) return '-';
    const date = new Date(dateStr);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// =====================================================
// INISIALISASI PETA LEAFLET
// =====================================================
function initMap(containerId, lat = -7.250445, lng = 112.768845, zoom = 12) {
    const mapContainer = document.getElementById(containerId);
    if (!mapContainer) return null;

    // Bersihkan instance lama jika ada
    if (mapContainer._leaflet_id) {
        return null;
    }

    const map = L.map(containerId, {
        center: [lat, lng],
        zoom: zoom,
        zoomControl: true
    });

    // Tile layer OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19
    }).addTo(map);

    return map;
}

// =====================================================
// ICON MARKER SESUAI LEVEL RISIKO
// =====================================================
function getMarkerIcon(level) {
    const colors = {
        rendah: '#198754',
        sedang: '#ffc107',
        tinggi: '#dc3545'
    };
    const color = colors[(level || '').toLowerCase()] || '#6c757d';

    return L.divIcon({
        className: '',
        html: `
            <div style="
                width: 28px;
                height: 28px;
                background: ${color};
                border-radius: 50% 50% 50% 0;
                transform: rotate(-45deg);
                border: 3px solid white;
                box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            "></div>
        `,
        iconSize: [28, 28],
        iconAnchor: [14, 28],
        popupAnchor: [0, -32]
    });
}

// =====================================================
// TAMBAHKAN MARKER KE PETA
// =====================================================
function addMarker(map, lat, lng, title, level, link) {
    if (!map || !lat || !lng) return;

    const icon = getMarkerIcon(level);
    const marker = L.marker([lat, lng], { icon: icon }).addTo(map);

    marker.bindPopup(`
        <div style="font-family: 'Inter', sans-serif; min-width: 160px;">
            <div style="font-weight: 700; font-size: 13px; margin-bottom: 4px;">${title}</div>
            <div style="margin-bottom: 8px;">${getRiskBadge(level)}</div>
            ${link ? `<a href="${link}" style="color: #198754; font-size: 12px; font-weight: 600; text-decoration: none;">
                <i class="bi bi-eye"></i> Lihat Detail →
            </a>` : ''}
        </div>
    `);

    return marker;
}

// =====================================================
// DEBOUNCE (untuk pencarian)
// =====================================================
function debounce(func, delay = 400) {
    let timer;
    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => func.apply(this, args), delay);
    };
}

// =====================================================
// COUNTER ANIMASI (untuk stat cards)
// =====================================================
function animateCounter(elementId, targetValue, duration = 1000) {
    const element = document.getElementById(elementId);
    if (!element) return;

    const startValue = 0;
    const startTime = performance.now();

    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3); // ease-out cubic
        const currentValue = Math.round(startValue + (targetValue - startValue) * eased);
        element.textContent = currentValue;

        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }

    requestAnimationFrame(update);
}

// =====================================================
// GET QUERY PARAMETER
// =====================================================
function getQueryParam(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

// =====================================================
// TOAST NOTIFICATION
// =====================================================
function showToast(message, type = 'success') {
    const iconMap = {
        success: 'bi-check-circle-fill',
        error: 'bi-x-circle-fill',
        warning: 'bi-exclamation-triangle-fill',
        info: 'bi-info-circle-fill'
    };
    const colorMap = {
        success: '#198754',
        error: '#dc3545',
        warning: '#ffc107',
        info: '#0dcaf0'
    };

    const toastEl = document.createElement('div');
    toastEl.style.cssText = `
        position: fixed;
        bottom: 24px;
        right: 24px;
        background: white;
        border-radius: 10px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.15);
        font-family: 'Inter', sans-serif;
        font-size: 13.5px;
        font-weight: 500;
        color: #1e293b;
        border-left: 4px solid ${colorMap[type]};
        z-index: 9999;
        animation: fadeInUp 0.3s ease;
        max-width: 320px;
    `;

    toastEl.innerHTML = `
        <i class="bi ${iconMap[type]}" style="color: ${colorMap[type]}; font-size: 18px; flex-shrink: 0;"></i>
        <span>${message}</span>
    `;

    document.body.appendChild(toastEl);

    setTimeout(() => {
        toastEl.style.transition = 'all 0.3s ease';
        toastEl.style.opacity = '0';
        toastEl.style.transform = 'translateY(10px)';
        setTimeout(() => toastEl.remove(), 300);
    }, 3500);
}
