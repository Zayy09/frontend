<?php
session_start();
require_once __DIR__ . '/includes/api.php';

$pageTitle = 'Catat Pemeriksaan';
$pageSubtitle = 'Formulir hasil pemantauan lingkungan';
$currentMenu = 'pemeriksaan';

// Handle POST
$alertMessage = '';
$alertType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postData = [
        'titik_risiko_id' => (int)$_POST['titik_risiko_id'],
        'petugas_id' => (int)($_POST['petugas_id'] ?? 1), // Default 1 for now
        'tanggal_pemeriksaan' => $_POST['tanggal_pemeriksaan'],
        'ditemukan_jentik' => isset($_POST['ditemukan_jentik']) && $_POST['ditemukan_jentik'] === 'ya' ? true : false,
        'kondisi_lingkungan' => $_POST['kondisi_lingkungan'],
        'tindakan_dilakukan' => $_POST['tindakan_dilakukan'],
        'status_akhir' => $_POST['status_akhir']
    ];

    $response = api_post('/pemeriksaan-risiko', $postData);
    
    if ($response['success']) {
        $_SESSION['flash_message'] = 'Data pemeriksaan berhasil disimpan!';
        header("Location: detail.php?id=" . $postData['titik_risiko_id']);
        exit;
    } else {
        $alertType = 'error';
        $alertMessage = 'Gagal menyimpan data: ' . ($response['error'] ?? 'Terjadi kesalahan pada server API.');
        // Display validation errors if any
        if (isset($response['data']['errors']) && is_array($response['data']['errors'])) {
            $alertMessage = "Validasi Gagal:<br>";
            foreach ($response['data']['errors'] as $field => $errors) {
                $alertMessage .= "- " . implode(', ', $errors) . "<br>";
            }
        }
    }
}

// Fetch titik risiko list for dropdown
$titikResp = api_get('/titik-risiko');
$titikList = $titikResp['success'] ? $titikResp['data'] : [];

$selectedTitikId = isset($_GET['titik_id']) ? (int)$_GET['titik_id'] : 0;

$extraHead = '
<style>
.form-card {
    max-width: 800px;
    margin: 0 auto;
}
.radio-group {
    display: flex;
    gap: 1rem;
    margin-top: 0.5rem;
}
.radio-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    background: white;
    padding: 0.75rem 1.25rem;
    border: 1px solid #cbd5e1;
    border-radius: var(--radius-md);
    transition: all 0.2s;
    font-weight: 500;
}
.radio-label:hover { border-color: var(--primary-color); }
input[type="radio"]:checked + .radio-label {
    border-color: var(--primary-color);
    background: var(--primary-light);
    color: var(--primary-dark);
}
input[type="radio"] { display: none; }
textarea.form-control { resize: vertical; min-height: 100px; }
</style>
';

include 'includes/header.php';
?>

<div class="form-card animate-fade-in">

    <?php if ($alertType === 'error'): ?>
        <div class="glass-card" style="background: #fef2f2; border-color: #fecaca; margin-bottom: 1.5rem;">
            <div style="display: flex; gap: 1rem; align-items: flex-start;">
                <i class="ph-fill ph-warning-circle" style="color: #ef4444; font-size: 1.5rem; margin-top: 2px;"></i>
                <div style="color: #991b1b;"><?= $alertMessage ?></div>
            </div>
        </div>
    <?php endif; ?>

    <div class="glass-card">
        <h3 style="margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px dashed #e2e8f0;">
            <i class="ph-fill ph-clipboard-text" style="color: var(--primary-color); margin-right: 0.5rem;"></i> Form Pencatatan
        </h3>
        
        <form action="" method="POST">
            <div class="form-group">
                <label class="form-label" for="titik_risiko_id">Lokasi Titik Risiko</label>
                <select name="titik_risiko_id" id="titik_risiko_id" class="form-control" required>
                    <option value="">-- Pilih Lokasi --</option>
                    <?php foreach ($titikList as $t): ?>
                        <option value="<?= $t['id'] ?>" <?= $selectedTitikId == $t['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['nama_titik'] ?? 'Tanpa Nama') ?> - <?= htmlspecialchars($t['alamat'] ?? '-') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($titikList)): ?>
                    <small style="color: #ef4444; display: block; margin-top: 5px;"><i class="ph-bold ph-warning"></i> Data lokasi belum tersedia atau gagal dimuat.</small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="tanggal_pemeriksaan">Tanggal Pemeriksaan</label>
                <input type="datetime-local" name="tanggal_pemeriksaan" id="tanggal_pemeriksaan" class="form-control" required value="<?= date('Y-m-d\TH:i') ?>">
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label">Apakah Ditemukan Jentik Nyamuk?</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="ditemukan_jentik" value="ya" required>
                        <div class="radio-label"><i class="ph-fill ph-bug" style="color: #ef4444;"></i> Ya, Ditemukan</div>
                    </label>
                    <label>
                        <input type="radio" name="ditemukan_jentik" value="tidak">
                        <div class="radio-label"><i class="ph-fill ph-shield-check" style="color: #22c55e;"></i> Tidak Ditemukan</div>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="kondisi_lingkungan">Kondisi Lingkungan</label>
                <textarea name="kondisi_lingkungan" id="kondisi_lingkungan" class="form-control" placeholder="Contoh: Terdapat banyak genangan air di pot bekas, selokan mampet..." required></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="tindakan_dilakukan">Tindakan yang Dilakukan</label>
                <textarea name="tindakan_dilakukan" id="tindakan_dilakukan" class="form-control" placeholder="Contoh: Menguras air di pot, memberikan bubuk abate, sosialisasi ke warga..." required></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="status_akhir">Rekomendasi Status Akhir</label>
                <select name="status_akhir" id="status_akhir" class="form-control" required>
                    <option value="">-- Pilih Status Akhir --</option>
                    <option value="aman">Aman - Risiko Rendah</option>
                    <option value="perlu pemantauan">Perlu Pemantauan - Risiko Sedang</option>
                    <option value="perlu tindakan">Perlu Tindakan - Risiko Tinggi</option>
                </select>
            </div>

            <div style="margin-top: 2.5rem; display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" onclick="history.back()" class="btn" style="background: white; border: 1px solid #cbd5e1; color: var(--text-main);">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="ph-bold ph-floppy-disk"></i> Simpan Hasil Pemeriksaan</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
