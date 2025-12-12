<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMIFAST - Immigration Fast Service</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <style>
        /* --- TAMPILAN WEB (LAYAR) --- */
        body { margin: 0; padding: 0; min-height: 100vh; overflow-x: hidden; font-family: 'Segoe UI', sans-serif; }
        
        #bg-video { position: fixed; right: 0; bottom: 0; min-width: 100%; min-height: 100%; z-index: -1; object-fit: cover; }
        .bg-image { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-size: cover; background-position: center; z-index: -1; background-color: rgba(0,0,0,0.5); background-blend-mode: multiply; }

        .navbar-glass { background: rgba(0, 0, 0, 0.7) !important; backdrop-filter: blur(8px); border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .admin-trigger { color: white; opacity: 0.1; transition: all 0.4s ease; cursor: default; font-size: 1.2rem; }
        .admin-trigger:hover { opacity: 1; cursor: pointer; color: #ffc107; transform: scale(1.2); }

        .card-form { border-radius: 15px; border: none; overflow: hidden; background: rgba(255, 255, 255, 0.95); }
        .header-bg { background: linear-gradient(135deg, #0d6efd, #0dcaf0); color: white; padding: 40px 20px; }

        .service-card { border: 3px solid transparent; transition: all 0.3s ease; cursor: pointer; background-size: cover !important; background-position: center !important; text-shadow: 1px 1px 3px rgba(0,0,0,0.8); }
        .service-card-dt { background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('/img/bg-drivethru.png'); color: white; height: 200px; }
        .service-card-we { background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('/img/bg-weekend.jpg'); color: white; height: 200px; }
        .form-check-input:checked + .service-card-dt, .service-card-dt:hover { border-color: #0d6efd; transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.3); }
        .form-check-input:checked + .service-card-we, .service-card-we:hover { border-color: #ffc107; transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.3); }

        .card-content-center { height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; }
        .hidden-radio { position: absolute; opacity: 0; width: 0; height: 0; }

        /* Sembunyikan area cetak di layar biasa */
        #print-area { display: none; }

        /* ========================================= */
        /* CSS KHUSUS CETAK (PRINT) - FIX FINAL      */
        /* ========================================= */
        @media print {
            @page { size: auto; margin: 0; }
            html, body { height: 100%; margin: 0 !important; padding: 0 !important; background: #fff !important; overflow: hidden; }
            
            /* Sembunyikan elemen web */
            body > *:not(#print-area) { display: none !important; }

            /* Tampilkan Area Cetak */
            #print-area {
                display: flex !important;
                justify-content: center;
                align-items: center;
                width: 100%;
                height: 100%;
                position: fixed; top: 0; left: 0;
                z-index: 9999;
                background: white;
            }

            /* Container Tiket */
            .ticket-container {
                width: 650px;
                border: 3px solid #000;
                position: relative; 
                background: white;
                color: black !important;
                font-family: sans-serif;
                overflow: hidden; 
            }

            /* --- WATERMARK LOGO (PERBAIKAN) --- */
            .watermark-logo {
                position: absolute;
                top: 50%; left: 50%; 
                transform: translate(-50%, -50%); /* Benar-benar di tengah */
                width: 300px; /* Ukuran tidak terlalu besar agar tidak menuhin */
                opacity: 0.1 !important; /* SANGAT SAMAR (Supaya tulisan terbaca) */
                z-index: 0; /* Paling belakang */
                display: block;
                /* Hapus filter grayscale jika ingin berwarna, tapi opacity rendah lebih aman */
            }

            /* Konten Tiket (Text) */
            .ticket-content {
                position: relative; 
                z-index: 2; /* Di atas watermark */
                background: transparent; /* Transparan agar watermark terlihat */
            }

            /* Header */
            .ticket-header {
                background-color: #f8f9fa !important;
                border-bottom: 2px solid #000;
                padding: 20px; text-align: center;
                -webkit-print-color-adjust: exact;
            }
            .ticket-header h2 { margin: 0; font-weight: 900; font-size: 22pt; text-transform: uppercase; color: #000; letter-spacing: 2px; }
            .ticket-header small { font-size: 11pt; font-weight: bold; color: #555; }

            /* Kode Booking */
            .booking-box {
                text-align: center; 
                border: 2px dashed #000; 
                padding: 15px; 
                margin: 25px 40px;
                background: rgba(255, 255, 255, 0.6) !important; /* Sedikit transparan */
                -webkit-print-color-adjust: exact;
            }
            .booking-box h1 { font-size: 38pt; margin: 0; font-weight: 900; letter-spacing: 4px; color: #000; }
            .booking-box small { font-weight: bold; text-transform: uppercase; font-size: 10pt; }

            /* Info Grid */
            .info-grid {
                display: grid; grid-template-columns: 1fr 1fr; gap: 20px;
                border-bottom: 2px solid #000; 
                padding: 0 40px 25px 40px; 
                margin-bottom: 10px;
            }
            .info-item label { display: block; font-size: 10pt; color: #444 !important; text-transform: uppercase; font-weight: bold; margin-bottom: 4px; }
            .info-item span { display: block; font-size: 14pt; font-weight: 800; color: #000; }

            /* Footer */
            .ticket-footer {
                text-align: center; padding: 15px; font-style: italic; font-size: 10pt; font-weight: bold; color: #000;
                background: white;
            }
        }
    </style>
</head>
<body>

    @if($bgType == 'video' && $bgFile)
        <video autoplay muted loop id="bg-video"><source src="{{ asset('uploads/' . $bgFile) }}" type="video/mp4"></video>
        <div style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.3); z-index:-1;"></div>
    @elseif($bgFile)
        <div class="bg-image" style="background-image: url('{{ asset('uploads/' . $bgFile) }}');"></div>
    @else
        <div class="bg-image" style="background-color: #f0f2f5;"></div>
    @endif

    <nav class="navbar navbar-expand-lg navbar-dark navbar-glass sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
                <img src="{{ asset('img/logo.png') }}" width="35" class="me-2" alt="Logo">
                <span>IMIFAST</span>
            </a>
            <div class="d-flex align-items-center">
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="badge bg-danger text-decoration-none p-2 rounded-pill">
                        <i class="fas fa-user-shield me-1"></i> Login Admin
                    </a>
                @else
                    <a href="{{ route('login') }}" class="admin-trigger" title="Akses Petugas"><i class="fas fa-fingerprint"></i></a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if(session('error')) <script>Swal.fire('Gagal!', '{{ session('error') }}', 'error');</script> @endif
                @if($errors->any())
                    <script>Swal.fire({ title: 'Mohon Maaf', text: '{{ $errors->first() }}', icon: 'warning', confirmButtonColor: '#d33', confirmButtonText: 'Baik, Saya Lengkapi' });</script>
                @endif

                <div class="card card-form shadow-lg">
                    <div class="header-bg text-center">
                        <h2 class="fw-bold mb-1">IMIFAST - Immigration Fast Service</h2>
                        <p class="mb-0 opacity-75">Mohon isi formulir pendaftaran di bawah ini dengan lengkap</p>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('book.store') }}" method="POST">
                            @csrf
                            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-concierge-bell me-2"></i>Pilih Layanan</h6>
                            <div class="row mb-4 g-3">
                                <div class="col-md-6">
                                    <div class="h-100 position-relative">
                                        <input class="form-check-input hidden-radio" type="radio" name="service_type" id="drive_thru" value="drive_thru" required onchange="checkQuota()">
                                        <label class="service-card service-card-dt rounded shadow-sm d-block" for="drive_thru">
                                            <div class="card-content-center p-3 text-center">
                                                <div class="fw-bold fs-4 text-white mb-1">Jiwo Mboten Mandhap</div>
                                                <div class="small fw-bold text-warning text-uppercase" style="letter-spacing: 1px;">(Drive Thru)</div>
                                                <div class="small text-white opacity-75">Tanpa Turun Kendaraan</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="h-100 position-relative">
                                        <input class="form-check-input hidden-radio" type="radio" name="service_type" id="weekend" value="weekend" required onchange="checkQuota()">
                                        <label class="service-card service-card-we rounded shadow-sm d-block" for="weekend">
                                            <div class="card-content-center p-3 text-center">
                                                <div class="fw-bold fs-4 text-white mb-1">Pengambilan Weekend</div>
                                                <div class="small fw-bold text-warning text-uppercase" style="letter-spacing: 1px;">(Sabtu & Minggu)</div>
                                                <div class="small text-white opacity-75">Khusus Pengambilan Paspor</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-calendar-alt me-2"></i>Jadwal Pengambilan</h6>
                            <div class="mb-4">
                                <label class="form-label">Tanggal Rencana Datang</label>
                                <input type="date" name="pickup_date" id="pickup_date" class="form-control form-control-lg" required onchange="checkQuota()">
                                <div id="quota_info" class="mt-2 fw-bold w-100 py-2 px-3 rounded d-none shadow-sm" style="font-size: 0.95rem;"></div>
                            </div>

                            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-user-edit me-2"></i>Data Diri</h6>
                            <div class="mb-3"><label>Nama Pemilik Paspor</label><input type="text" name="passport_name" class="form-control" placeholder="Sesuai Paspor" required></div>
                            <div class="mb-3"><label>Nama Pengambil</label><input type="text" name="collector_name" class="form-control" placeholder="Orang yang datang" required></div>
                            <div class="mb-3"><label>Nomor HP / WhatsApp</label><input type="number" name="phone" class="form-control" placeholder="08xxxx" required></div>

                            <button type="submit" id="btn_submit" class="btn btn-primary w-100 py-3 fw-bold mt-3 shadow scale-hover">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Pendaftaran
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success_data'))
    @php $ticket = session('success_data'); @endphp
    <div class="modal fade" id="ticketModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-check-circle me-2"></i>TIKET BERHASIL DIBUAT</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body p-4 text-center">
                    <div class="p-3 mb-3 bg-light border border-2 border-success border-dashed">
                        <h2 class="display-5 fw-bold text-success mb-0">{{ $ticket->booking_code }}</h2>
                        <small class="text-uppercase fw-bold text-muted">KODE BOOKING</small>
                    </div>

                    <div class="alert alert-warning border-warning text-dark fw-bold mb-3 p-2 small">
                        <i class="fas fa-id-badge me-2"></i>TUNJUKKAN TIKET INI KEPADA PETUGAS
                    </div>

                    <div class="text-start row g-2 small border rounded p-3 bg-white mx-1">
                        <div class="col-6 mb-2">
                            <span class="text-muted d-block">Layanan:</span>
                            <span class="fw-bold text-dark">{{ $ticket->service_type == 'drive_thru' ? 'DRIVE THRU' : 'WEEKEND' }}</span>
                        </div>
                        <div class="col-6 mb-2">
                            <span class="text-muted d-block">Tgl Ambil:</span>
                            <span class="fw-bold text-primary">{{ \Carbon\Carbon::parse($ticket->pickup_date)->format('d/m/Y') }}</span>
                        </div>
                        <div class="col-12 border-bottom my-1"></div>
                        <div class="col-6">
                            <span class="text-muted d-block">Nama Paspor:</span>
                            <span class="fw-bold text-dark">{{ $ticket->passport_name }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block">Nama Pengambil:</span>
                            <span class="fw-bold text-dark">{{ $ticket->collector_name }}</span>
                        </div>
                    </div>

                    <div class="text-center mt-3 text-muted small">
                        <i>Dibuat: {{ $ticket->created_at->timezone('Asia/Jakarta')->format('d-m-Y H:i') }} WIB</i>
                    </div>
                </div>

                <div class="modal-footer justify-content-center bg-light">
                    <button type="button" class="btn btn-primary w-100 fw-bold py-2" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>CETAK / SIMPAN PDF
                    </button>
                    <button type="button" class="btn btn-secondary w-100 mt-2" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div id="print-area">
        <div class="ticket-container">
            <img src="{{ asset('img/logo.png') }}" class="watermark-logo" alt="Watermark">
            
            <div class="ticket-content">
                <div class="ticket-header">
                    <h2>BUKTI ANTRIAN</h2>
                    <small>IMIFAST - Immigration Fast Service</small>
                </div>

                <div class="ticket-body">
                    <div class="booking-box">
                        <small>KODE BOOKING</small>
                        <h1>{{ $ticket->booking_code }}</h1>
                    </div>

                    <div class="info-grid">
                        <div class="info-item">
                            <label>Jenis Layanan</label>
                            <span>{{ $ticket->service_type == 'drive_thru' ? 'DRIVE THRU' : 'WEEKEND' }}</span>
                        </div>
                        <div class="info-item">
                            <label>Tanggal Ambil</label>
                            <span>{{ \Carbon\Carbon::parse($ticket->pickup_date)->format('d/m/Y') }}</span>
                        </div>
                        <div class="info-item">
                            <label>Nama Paspor</label>
                            <span>{{ $ticket->passport_name }}</span>
                        </div>
                        <div class="info-item">
                            <label>Nama Pengambil</label>
                            <span>{{ $ticket->collector_name }}</span>
                        </div>
                    </div>

                    <p style="text-align: center; margin-top: 20px; font-weight: bold;">
                        HARAP DATANG SESUAI JADWAL - TUNJUKKAN KEPADA PETUGAS
                    </p>
                </div>

                <div class="ticket-footer">
                    Dicetak: {{ now()->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB - IMIFAST
                </div>
            </div>
        </div>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function(){
            @if(session('success_data'))
                var myModal = new bootstrap.Modal(document.getElementById('ticketModal'));
                myModal.show();
                
                var duration = 3000; var end = Date.now() + duration;
                (function frame() {
                    confetti({ particleCount: 3, angle: 60, spread: 55, origin: { x: 0 } });
                    confetti({ particleCount: 3, angle: 120, spread: 55, origin: { x: 1 } });
                    if (Date.now() < end) requestAnimationFrame(frame);
                }());
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Silakan simpan tiket Anda.', timer: 2500, showConfirmButton: false });
            @endif
        });

        document.getElementById('btn_submit').addEventListener('click', function(e) {
            const dt = document.getElementById('drive_thru').checked;
            const we = document.getElementById('weekend').checked;
            if (!dt && !we) {
                e.preventDefault();
                Swal.fire({ title: 'Layanan Belum Dipilih!', text: 'Mohon klik salah satu kartu layanan di atas.', icon: 'warning', confirmButtonColor: '#d33' });
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return false;
            }
            if (!document.getElementById('pickup_date').value) {
                e.preventDefault();
                Swal.fire({ title: 'Tanggal Kosong!', text: 'Wajib memilih tanggal pengambilan.', icon: 'warning', confirmButtonColor: '#d33' });
                return false;
            }
        });

        function checkQuota() {
            const dateVal = document.getElementById('pickup_date').value;
            const infoBox = document.getElementById('quota_info');
            const btn = document.getElementById('btn_submit');
            if (!dateVal) return;

            infoBox.className = 'mt-2 fw-bold w-100 py-2 px-3 rounded small bg-info text-white';
            infoBox.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengecek...';
            btn.disabled = true;

            fetch(`/check-quota?date=${dateVal}`).then(r => r.json()).then(d => {
                if (d.is_closed) {
                    infoBox.className = 'mt-2 fw-bold w-100 py-2 px-3 rounded small bg-danger text-white';
                    infoBox.innerHTML = `<i class="fas fa-door-closed me-2"></i>MAAF, LAYANAN TUTUP / LIBUR`;
                    btn.disabled = true;
                } else if (d.remaining <= 0) {
                    infoBox.className = 'mt-2 fw-bold w-100 py-2 px-3 rounded small bg-danger text-white';
                    infoBox.innerHTML = `<i class="fas fa-ban me-2"></i>KUOTA PENUH (0 Slot)`;
                    btn.disabled = true;
                } else {
                    infoBox.className = 'mt-2 fw-bold w-100 py-2 px-3 rounded small bg-success text-white';
                    infoBox.innerHTML = `<i class="fas fa-check-circle me-2"></i>DIBUKA | Sisa Kuota: <b>${d.remaining}</b> Slot`;
                    btn.disabled = false;
                }
            });
        }
    </script>
</body>
</html>