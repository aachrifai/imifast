<!DOCTYPE html>
<html lang="id">
<head>
    <title>Pengaturan Tampilan</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .sidebar { min-height: 100vh; background: #1a1d20; color: white; }
        .sidebar-brand { background: #2c3034; padding: 15px; border-radius: 10px; text-align: center; border: 1px solid #495057; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.3); }
        .sidebar-brand h4 { margin: 0; font-weight: 800; letter-spacing: 2px; color: #ffc107; }
        .sidebar-brand small { font-size: 0.65rem; color: #adb5bd; text-transform: uppercase; letter-spacing: 1px;}
        .nav-link { color: rgba(255,255,255,0.7); margin-bottom: 5px; border-radius: 5px; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { background: #0d6efd; color: white; transform: translateX(5px); }
    </style>
</head>
<body>

<div class="d-flex">
    <div class="sidebar p-3 d-none d-md-block" style="width: 250px;">
        <div class="sidebar-brand">
            <i class="fas fa-passport fa-2x mb-2 text-white"></i>
            <h4>IMG-SYS</h4>
            <small>Sistem Pelayanan Paspor</small>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fas fa-columns me-2"></i>Dashboard Utama</a></li>
            <li class="nav-item"><a href="#" class="nav-link active"><i class="fas fa-image me-2"></i>Ganti Banner</a></li>
            <li class="nav-item"><a href="{{ route('home') }}" target="_blank" class="nav-link"><i class="fas fa-external-link-alt me-2"></i>Lihat Website</a></li>
            <li class="nav-item mt-4 pt-4 border-top border-secondary">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf <button class="btn btn-danger w-100 text-start fw-bold"><i class="fas fa-power-off me-2"></i>KELUAR</button>
                </form>
            </li>
        </ul>
    </div>

    <div class="flex-grow-1 bg-light p-4">
        <h3 class="fw-bold text-dark mb-4">PENGATURAN TAMPILAN USER</h3>

        @if(session('swal_success')) <script>Swal.fire('Sukses', '{{ session('swal_success') }}', 'success');</script> @endif

        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 border-top border-4 border-warning">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-cloud-upload-alt me-2 text-warning"></i>Upload Background Baru</h5>
                        <p class="text-muted small">Anda bisa mengganti tampilan halaman depan (User) dengan Foto atau Video agar lebih menarik.</p>
                        
                        <form action="{{ route('admin.bg_update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-bold">Pilih File</label>
                                <input type="file" name="bg_file" class="form-control" required accept="image/*,video/mp4">
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i> Format: .JPG, .PNG (Foto) atau .MP4 (Video). Maksimal 20MB.
                                </div>
                            </div>
                            <button class="btn btn-warning w-100 fw-bold py-2"><i class="fas fa-save me-2"></i> SIMPAN PERUBAHAN</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="alert alert-info border-0 shadow-sm">
                    <h6 class="fw-bold"><i class="fas fa-lightbulb me-2"></i>Tips Tampilan:</h6>
                    <ul class="mb-0 small ps-3">
                        <li class="mb-1">Gunakan gambar yang agak gelap agar tulisan website terbaca.</li>
                        <li class="mb-1">Jika Video, gunakan durasi pendek agar loading cepat.</li>
                        <li>Ukuran ideal: 1920x1080 pixel.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>