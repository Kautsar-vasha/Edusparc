@extends('layouts.app')

@section('content')
<style>
    #reader { width: 100%; border-radius: 8px; overflow: hidden; border: 2px dashed #0d6efd; background: #f8f9fa; min-height: 250px; display: flex; align-items: center; justify-content: center;}
    .log-container { max-height: 250px; overflow-y: auto; }

    /* Animasi pop-up berhasil */
    @keyframes flashGreen {
        0% { background-color: #d1e7dd; }
        100% { background-color: #ffffff; }
    }
    .flash-success { animation: flashGreen 1.5s ease-out; }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0"><i class="bi bi-upc-scan text-primary me-2"></i>Quick Scanner</h3>
            <p class="text-muted small mt-1 mb-0">Atur poin di awal, lalu scan siswa secara massal dengan cepat.</p>
        </div>
        <a href="/pelanggaran" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali ke Riwayat</a>
    </div>

    <div id="scan_alert" class="alert alert-success shadow-sm border-0 d-none" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><span id="scan_alert_msg" class="fw-bold"></span>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm p-4 h-100 border-top border-4 border-primary">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-gear-fill text-secondary me-2"></i> Setting Target Poin</h5>
                    <span class="badge bg-light text-success border border-success"><i class="bi bi-save2"></i> Tersimpan Otomatis</span>
                </div>
                <div class="alert alert-info small py-2 border-0">
                    Atur form ini terlebih dahulu. Sistem akan mengingat pengaturan Anda secara otomatis.
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold small">Jenis Poin</label>
                        <select id="set_jenis" class="form-select fw-bold">
                            <option value="negatif" class="text-danger">Pelanggaran (-)</option>
                            <option value="positif" class="text-success" selected>Kebaikan (+)</option>
                        </select>
                    </div>
                    <div class="col-8">
                        <label class="form-label fw-bold small">Nama Aktivitas / Kegiatan</label>
                        <input type="text" id="set_type" class="form-control" placeholder="Cth: Sholat Dhuha / Terlambat" required>
                    </div>
                    <div class="col-4">
                        <label class="form-label fw-bold small">Poin</label>
                        <input type="number" id="set_points" class="form-control" placeholder="0" min="1" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold small">Kategori</label>
                        <select id="set_category" class="form-select">
                            <option value="Etika/Perilaku">Etika/Perilaku</option>
                            <option value="Administratif">Administratif</option>
                            <option value="Prestasi">Prestasi</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold small">Catatan Tambahan (Opsional)</label>
                        <input type="text" id="set_motivation" class="form-control" placeholder="Pesan pembinaan singkat...">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm p-4 h-100">
                <h5 class="fw-bold mb-3"><i class="bi bi-qr-code-scan text-success me-2"></i>2. Area Scanner</h5>

                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active small fw-bold" id="tab-usb-btn" data-bs-toggle="pill" data-bs-target="#tab-usb" type="button"><i class="bi bi-upc me-1"></i>Alat Scanner USB</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link small fw-bold" id="tab-kamera-btn" data-bs-toggle="pill" data-bs-target="#tab-kamera" type="button"><i class="bi bi-camera me-1"></i>Kamera Web/HP</button>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="tab-usb">
                        <div class="input-group input-group-lg mb-3 mt-3">
                            <span class="input-group-text bg-dark text-white border-0"><i class="bi bi-upc-scan"></i></span>
                            <input type="text" id="usb_input" class="form-control border-dark border-2 text-center fw-bold" placeholder="Tembak Kartu Siswa di sini..." autocomplete="off">
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab-kamera">
                        <div id="reader" class="mb-3 mt-3">
                            <div class="text-muted small" id="kamera_status">Kamera siap dihidupkan...</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <h6 class="fw-bold text-muted small"><i class="bi bi-clock-history me-1"></i>Riwayat Scan Terakhir (Real-time)</h6>
                    <div class="log-container border rounded bg-light p-2" id="log_area">
                        <div class="text-center text-muted small py-3" id="empty_log">Belum ada siswa yang discan pada sesi ini.</div>
                        </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    // ==========================================
    // 1. FITUR AUTO-SAVE SETTINGAN (LOCAL STORAGE)
    // ==========================================
    const settingInputs = ['set_jenis', 'set_type', 'set_points', 'set_category', 'set_motivation'];

    window.onload = function() {
        settingInputs.forEach(id => {
            if(localStorage.getItem('edusparc_' + id)) {
                document.getElementById(id).value = localStorage.getItem('edusparc_' + id);
            }
        });
        document.getElementById('usb_input').focus();
    };

    settingInputs.forEach(id => {
        document.getElementById(id).addEventListener('input', function() {
            localStorage.setItem('edusparc_' + id, this.value);
        });
    });

    // ==========================================
    // 2. LOGIKA ANTI-SPAM (COOLDOWN) & AJAX
    // ==========================================
    let lastScanCode = "";
    let lastScanTime = 0;
    const COOLDOWN_WAKTU = 3000; // Jeda 3 detik

    function prosesScanData(nis) {
        let jenis = document.getElementById('set_jenis').value;
        let type = document.getElementById('set_type').value;
        let points = document.getElementById('set_points').value;
        let cat = document.getElementById('set_category').value;
        let mot = document.getElementById('set_motivation').value;

        if(!type || !points) {
            alert('STOP! Silakan isi "Nama Aktivitas" dan "Jumlah Poin" terlebih dahulu.');
            return;
        }

        fetch('/scanner/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                nis: nis, jenis_poin: jenis, type: type, points: points, category: cat, motivation: mot
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                let audio = new Audio('https://www.soundjay.com/buttons/button-09.wav');
                audio.play().catch(e => {});

                let emptyLog = document.getElementById('empty_log');
                if (emptyLog) emptyLog.style.display = 'none';

                let badge = data.jenis == 'positif' ? `<span class="badge bg-success">+${data.points} Poin</span>` : `<span class="badge bg-danger">-${data.points} Poin</span>`;
                let logArea = document.getElementById('log_area');
                let newLog = document.createElement('div');
                newLog.className = 'p-2 border-bottom border-success mb-1 rounded small flash-success fw-semibold';
                newLog.innerHTML = `<i class="bi bi-check-circle-fill text-success me-2"></i> <b>${data.student_name}</b> (${data.class}) berhasil discan! ${badge}`;

                logArea.insertBefore(newLog, logArea.firstChild);

                let alertBox = document.getElementById('scan_alert');
                document.getElementById('scan_alert_msg').innerText = `Berhasil mencatat karakter untuk ${data.student_name}!`;
                alertBox.classList.remove('d-none');

                setTimeout(() => { alertBox.classList.add('d-none'); }, 3000);
            } else {
                alert('Peringatan: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // ==========================================
    // 3. SETUP KAMERA CANGGIH (CORE API)
    // ==========================================
    const html5QrCode = new Html5Qrcode("reader");

    function onScanSuccess(decodedText) {
        let currentTime = new Date().getTime();

        // Anti-Spam
        if (decodedText === lastScanCode && (currentTime - lastScanTime) < COOLDOWN_WAKTU) {
            return;
        }

        lastScanCode = decodedText;
        lastScanTime = currentTime;
        prosesScanData(decodedText);
    }

    // Nyalakan kamera HANYA saat tab kamera diklik
    document.getElementById('tab-kamera-btn').addEventListener('shown.bs.tab', function (e) {
        document.getElementById('kamera_status').style.display = 'none';
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            onScanSuccess
        ).catch(err => {
            document.getElementById('reader').innerHTML = '<div class="alert alert-danger m-3 small"><i class="bi bi-exclamation-triangle"></i> Kamera gagal diakses. Pastikan izin diberikan!</div>';
        });
    });

    // Matikan kamera saat kembali ke tab USB
    document.getElementById('tab-usb-btn').addEventListener('shown.bs.tab', function (e) {
        if(html5QrCode.isScanning) {
            html5QrCode.stop().then(() => {
                document.getElementById('kamera_status').style.display = 'block';
            }).catch(err => console.error(err));
        }
        setTimeout(() => { document.getElementById('usb_input').focus(); }, 200);
    });

    // ==========================================
    // 4. SETUP ALAT USB SCANNER
    // ==========================================
    const usbInput = document.getElementById('usb_input');
    usbInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            let nis_scanned = this.value;
            if(nis_scanned.trim() !== '') {
                prosesScanData(nis_scanned);
                this.value = '';
            }
        }
    });
</script>
@endsection
