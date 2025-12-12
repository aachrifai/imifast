<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Admin</title>
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
        .row-completed { background-color: #d1e7dd !important; }
        .row-completed td { opacity: 0.6; text-decoration: line-through; color: #6c757d; }
        .row-completed .no-strike { text-decoration: none !important; opacity: 1 !important; }
        .modal-content { text-decoration: none !important; opacity: 1 !important; text-align: left; }
    </style>
</head>
<body>

<div class="d-flex">
    <div class="sidebar p-3 d-none d-md-block" style="width: 250px;">
        <div class="sidebar-brand">
            <i class="fas fa-passport fa-2x mb-2 text-white"></i>
            <h4>ADMIN-SYS</h4>
            <small>IMIFAST</small>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item"><a href="#" class="nav-link active"><i class="fas fa-columns me-2"></i>Dashboard Utama</a></li>
            
            <li class="nav-item"><a href="{{ route('admin.banner') }}" class="nav-link"><i class="fas fa-image me-2"></i>Ganti Banner</a></li>
            
            <li class="nav-item"><a href="{{ route('home') }}" target="_blank" class="nav-link"><i class="fas fa-external-link-alt me-2"></i>Lihat Website</a></li>
            <li class="nav-item mt-4 pt-4 border-top border-secondary">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf <button class="btn btn-danger w-100 text-start fw-bold"><i class="fas fa-power-off me-2"></i>LOGOUT</button>
                </form>
            </li>
        </ul>
    </div>

    <div class="flex-grow-1 bg-light p-4">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark mb-0">PUSAT KONTROL PELAYANAN</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.print') }}" target="_blank" class="btn btn-dark btn-sm fw-bold"><i class="fas fa-file-pdf me-1"></i> LAPORAN PDF</a>
                <button type="button" class="btn btn-danger btn-sm fw-bold" onclick="confirmReset()">
                    <i class="fas fa-trash-alt me-1"></i> HAPUS SEMUA DATA
                </button>
                <form id="formResetData" action="{{ route('admin.reset') }}" method="POST" class="d-none">@csrf</form>
            </div>
        </div>

        @if(session('swal_success')) <script>Swal.fire('Sukses', '{{ session('swal_success') }}', 'success');</script> @endif
        
        @if(session('swal_info')) 
            <script>
                Swal.fire({
                    title: 'Info Tanggal',
                    html: `{!! nl2br(session('swal_info')) !!}`, // Convert newline to <br>
                    icon: 'info'
                });
            </script> 
        @endif

        <div class="card shadow-sm border-0 mb-4 rounded-3 border-start border-4 border-primary">
            <div class="card-body bg-white py-3">
                <h6 class="fw-bold mb-3 border-bottom pb-2 text-primary"><i class="fas fa-clock me-2"></i>Pengaturan Jadwal & Cek Kuota</h6>
                <div class="row g-3">
                    
                    <div class="col-md-8 border-end">
                        <form action="{{ route('admin.quota') }}" method="POST" class="row g-2 align-items-end">
                            @csrf
                            <div class="col-4"> 
                                <label class="small fw-bold">Pilih Tanggal</label>
                                <input type="date" name="date" class="form-control" required> 
                            </div>
                            <div class="col-3"> 
                                <label class="small fw-bold">Set Kuota</label>
                                <input type="number" name="quota" class="form-control" value="50"> 
                            </div>
                            <div class="col-3"> 
                                <label class="small fw-bold">Status</label>
                                <select name="status" class="form-select"><option value="open">Buka</option><option value="closed">Tutup</option></select> 
                            </div>
                            <div class="col-2"> 
                                <button class="btn btn-primary w-100 fw-bold mt-4">SIMPAN</button> 
                            </div>
                        </form>
                    </div>

                    <div class="col-md-4 ps-md-4">
                        <label class="small fw-bold mb-2">Cek Sisa Kuota</label>
                        <form action="{{ route('admin.check_status') }}" method="POST" class="d-flex gap-2">
                            @csrf
                            <input type="date" name="date" class="form-control" required>
                            <button class="btn btn-secondary fw-bold">CEK</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow border-0 rounded-3">
            <div class="card-header bg-white py-3 border-bottom"><h5 class="mb-0 fw-bold text-dark">DATA PEMOHON MASUK</h5></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Status</th>
                                <th>Kode Tiket</th>
                                <th>Layanan</th>
                                <th>Tgl Ambil</th>
                                <th>Waktu Input</th>
                                <th>Data Pemohon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $data)
                            <tr class="{{ $data->status == 'completed' ? 'row-completed' : '' }}">
                                <td class="ps-3 no-strike">
                                    <form action="{{ route('admin.toggle_status', $data->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        @if($data->status == 'pending')
                                            <button class="btn btn-outline-secondary btn-sm" title="Tandai Selesai"><i class="far fa-square"></i></button>
                                        @else
                                            <button class="btn btn-success btn-sm" title="Batal (Undo)"><i class="fas fa-check-square"></i></button>
                                        @endif
                                    </form>
                                </td>
                                <td class="fw-bold font-monospace">{{ $data->booking_code }}</td>
                                <td class="no-strike">
                                    <span class="badge {{ $data->service_type == 'drive_thru' ? 'bg-info' : 'bg-warning' }} text-dark">
                                        {{ $data->service_type == 'drive_thru' ? 'Drive Thru' : 'Weekend' }}
                                    </span>
                                </td>
                                <td class="fw-bold">{{ \Carbon\Carbon::parse($data->pickup_date)->format('d/m/Y') }}</td>
                                <td>
                                    <div class="fw-bold small">{{ $data->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB</div>
                                    <small>{{ $data->created_at->timezone('Asia/Jakarta')->format('d/m') }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $data->collector_name }}</div>
                                    <small class="text-muted">Milik: <b>{{ $data->passport_name }}</b></small>
                                </td>
                                <td class="no-strike">
                                    @if($data->status == 'pending')
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $data->id }}" title="Edit Data"><i class="fas fa-edit"></i></button>
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled title="Terkunci"><i class="fas fa-lock"></i></button>
                                    @endif
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete('{{ $data->id }}')" title="Hapus Permanen"><i class="fas fa-trash"></i></button>
                                    <form id="delForm{{ $data->id }}" action="{{ route('admin.delete', $data->id) }}" method="POST" class="d-none">@csrf @method('DELETE')</form>

                                    @if($data->status == 'pending')
                                    <div class="modal fade" id="editModal{{ $data->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Data Pemohon</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('admin.update', $data->id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-2"><label>Nama Paspor</label><input type="text" name="passport_name" class="form-control" value="{{ $data->passport_name }}"></div>
                                                        <div class="mb-2"><label>Nama Pengambil</label><input type="text" name="collector_name" class="form-control" value="{{ $data->collector_name }}"></div>
                                                        <div class="mb-2"><label>HP/WA</label><input type="number" name="phone" class="form-control" value="{{ $data->phone }}"></div>
                                                        <div class="mb-2"><label>Tanggal</label><input type="date" name="pickup_date" class="form-control" value="{{ $data->pickup_date }}"></div>
                                                    </div>
                                                    <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan Perubahan</button></div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada data masuk.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({ title: 'Hapus Data?', text: "Data ini akan dihapus permanen!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, Hapus' }).then((result) => { if (result.isConfirmed) document.getElementById('delForm'+id).submit(); })
    }
    function confirmReset() {
        Swal.fire({ title: 'RESET TOTAL?', text: "SEMUA DATA AKAN DIHAPUS PERMANEN!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'YA, HAPUS SEMUA' }).then((result) => { if (result.isConfirmed) document.getElementById('formResetData').submit(); })
    }
</script>
</body>
</html>