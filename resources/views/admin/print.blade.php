<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan IMIFAST</title>
    
    <style>
        /* Font yang bersih untuk laporan sistem */
        body { font-family: Arial, Helvetica, sans-serif; font-size: 11pt; margin: 20px; }
        
        /* HEADER SIMPEL (Tanpa Logo) */
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            border-bottom: 2px solid #000; 
            padding-bottom: 10px;
        }
        .header h1 { margin: 0; font-size: 16pt; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; }
        .header h2 { margin: 5px 0; font-size: 12pt; font-weight: normal; text-transform: uppercase; }
        .header .meta { font-size: 10pt; color: #333; margin-top: 5px; font-style: italic; }

        /* TABEL VERTIKAL */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #444; padding: 8px; text-align: left; vertical-align: middle; font-size: 10pt; }
        th { background-color: #e0e0e0; text-align: center; font-weight: bold; }
        
        /* UTILS */
        .text-center { text-align: center; }
        .font-mono { font-family: 'Courier New', Courier, monospace; letter-spacing: 1px; font-weight: bold; }
        .status-ok { color: green; font-weight: bold; }
        .status-no { color: red; font-style: italic; }

        /* Style untuk Nama */
        .pengambil { font-size: 11pt; font-weight: bold; text-transform: uppercase; display: block; }
        .pemilik { font-size: 9pt; color: #555; display: block; margin-top: 2px; }

        /* TOMBOL CETAK (Hilang saat diprint) */
        .no-print { margin-bottom: 20px; text-align: center; }
        .btn-print {
            background: #333; color: white; border: none; padding: 10px 20px; 
            cursor: pointer; font-weight: bold; border-radius: 4px;
        }
        .btn-print:hover { background: #000; }

        /* SETTINGAN KERTAS */
        @media print {
            .no-print { display: none; }
            @page { 
                size: A4 portrait; /* Format Vertikal */
                margin: 1.5cm; 
            }
            body { margin: 0; }
        }
    </style>
</head>
<body>

    @php \Carbon\Carbon::setLocale('id'); @endphp

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">🖨️ Cetak Data</button>
    </div>

    <div class="header">
        <h1>SISTEM IMIFAST - IMMIGRATION FAST SERVICE</h1>
        <h2>Laporan Data Pengambilan Paspor</h2>
        <div class="meta">
            Dicetak pada: {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y - H:i') }} WIB
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode</th>
                <th width="15%">Waktu Daftar</th>
                <th width="15%">Layanan</th>
                <th width="15%">Tgl Ambil</th>
                <th width="20%">Data Pengambil</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $key => $data)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td class="text-center font-mono">{{ $data->booking_code }}</td>
                
                <td class="text-center" style="font-size: 9pt;">
                    {{ $data->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y') }}<br>
                    {{ $data->created_at->timezone('Asia/Jakarta')->format('H:i') }}
                </td>

                <td class="text-center">
                    @if($data->service_type == 'drive_thru')
                        Drive Thru
                    @else
                        Weekend
                    @endif
                </td>

                <td class="text-center">
                    {{ \Carbon\Carbon::parse($data->pickup_date)->translatedFormat('d/m/Y') }}
                </td>

                <td>
                    <span class="pengambil">{{ $data->collector_name }}</span>
                    <span class="pemilik">Milik: {{ $data->passport_name }}</span>
                </td>
                
                <td class="text-center">
                    @if($data->status == 'completed')
                        <span class="status-ok">SUDAH</span>
                    @else
                        <span class="status-no">BELUM</span>
                    @endif
                </td>
            </tr>
            @endforeach

            @if($bookings->isEmpty())
            <tr>
                <td colspan="7" class="text-center" style="padding: 20px; font-style:italic;">
                    -- Belum ada data masuk --
                </td>
            </tr>
            @endif
        </tbody>
    </table>

</body>
</html>