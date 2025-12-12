<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Booking;
use App\Models\Quota;
use App\Models\Setting;
use Illuminate\Support\Facades\File;

class ImigrasiController extends Controller
{
    // --- HALAMAN USER ---

    public function index()
    {
        $bgType = Setting::where('key', 'bg_type')->value('value') ?? 'image';
        $bgFile = Setting::where('key', 'bg_file')->value('value');
        return view('welcome', compact('bgType', 'bgFile'));
    }

    public function store(Request $request)
    {
        // 1. VALIDASI DENGAN PESAN BAHASA INDONESIA KHUSUS
        $request->validate([
            'service_type' => 'required',
            'passport_name' => 'required',
            'collector_name' => 'required',
            'phone' => 'required|numeric',
            'pickup_date' => 'required|date|after_or_equal:today',
        ], [
            // PESAN CUSTOM JIKA LUPA MEMILIH LAYANAN
            'service_type.required' => 'Mohon maaf, Anda belum memilih jenis layanan (Drive Thru atau Weekend). Silakan klik salah satu kartu layanan.',
            'passport_name.required' => 'Nama pemilik paspor wajib diisi.',
            'collector_name.required' => 'Nama pengambil wajib diisi.',
            'phone.required' => 'Nomor HP/WA wajib diisi.',
            'pickup_date.required' => 'Tanggal pengambilan wajib dipilih.',
            'pickup_date.after_or_equal' => 'Tanggal tidak boleh di masa lalu.',
        ]);

        $date = $request->pickup_date;
        $quotaSetting = Quota::where('date', $date)->first();

        if ($quotaSetting && $quotaSetting->is_closed) {
            return back()->with('error', 'Layanan pada tanggal tersebut LIBUR / TUTUP.');
        }

        $limit = $quotaSetting ? $quotaSetting->quota : 50; 
        $currentBookings = Booking::where('pickup_date', $date)->count();

        if ($currentBookings >= $limit) {
            return back()->with('error', "Kuota Penuh! Silakan pilih tanggal lain.");
        }

        $dayOfWeek = date('N', strtotime($date));
        if ($request->service_type == 'weekend' && $dayOfWeek < 6) {
            return back()->with('error', 'Layanan Weekend Service HANYA hari Sabtu & Minggu.');
        }

        $prefix = ($request->service_type == 'drive_thru') ? 'D' : 'W';
        $code = $prefix . '-' . date('dm', strtotime($date)) . '-' . strtoupper(Str::random(3));

        $booking = new Booking();
        $booking->booking_code = $code;
        $booking->service_type = $request->service_type;
        $booking->passport_name = $request->passport_name;
        $booking->collector_name = $request->collector_name;
        $booking->phone = $request->phone;
        $booking->pickup_date = $request->pickup_date;
        $booking->status = 'pending';
        $booking->save();

        return back()->with('success_data', $booking);
    }

    public function checkQuota(Request $request)
    {
        $date = $request->query('date');
        if (!$date) return response()->json(['error' => 'Tanggal wajib'], 400);

        $quotaSetting = Quota::where('date', $date)->first();
        $maxQuota = $quotaSetting ? $quotaSetting->quota : 50;
        $isClosed = $quotaSetting ? $quotaSetting->is_closed : false;
        $booked = Booking::where('pickup_date', $date)->count();
        $remaining = max(0, $maxQuota - $booked);

        return response()->json([
            'date' => $date,
            'is_closed' => $isClosed,
            'remaining' => $remaining,
            'total' => $maxQuota
        ]);
    }

    // --- HALAMAN ADMIN ---

    public function dashboard()
    {
        $bookings = Booking::orderByRaw("FIELD(status, 'pending', 'completed')")
                           ->orderBy('pickup_date', 'asc')
                           ->get();
        return view('admin.dashboard', compact('bookings'));
    }

    // PAGE BARU: BANNER SETTINGS
    public function bannerPage()
    {
        return view('admin.banner');
    }

    public function updateBackground(Request $request)
    {
        $request->validate([
            'bg_file' => 'required|file|mimes:jpg,jpeg,png,mp4|max:20480',
        ]);

        $file = $request->file('bg_file');
        $extension = $file->getClientOriginalExtension();
        $type = in_array(strtolower($extension), ['mp4']) ? 'video' : 'image';
        
        $fileName = 'bg_' . time() . '.' . $extension;
        $file->move(public_path('uploads'), $fileName);

        $oldFile = Setting::where('key', 'bg_file')->value('value');
        if ($oldFile && File::exists(public_path('uploads/' . $oldFile))) {
            File::delete(public_path('uploads/' . $oldFile));
        }

        Setting::updateOrCreate(['key' => 'bg_type'], ['value' => $type]);
        Setting::updateOrCreate(['key' => 'bg_file'], ['value' => $fileName]);

        return back()->with('swal_success', 'Background Tampilan User Berhasil Diganti!');
    }

    public function setQuota(Request $request)
    {
        $request->validate(['date' => 'required|date', 'quota' => 'required|integer']);
        Quota::updateOrCreate(
            ['date' => $request->date],
            ['quota' => $request->quota, 'is_closed' => $request->status == 'closed']
        );
        return back()->with('swal_success', 'Pengaturan tanggal berhasil disimpan.');
    }

    // FITUR REVISI: CEK STATUS DETAIL
    public function checkQuotaStatus(Request $request)
    {
        $date = $request->date;
        $q = Quota::where('date', $date)->first();
        
        // Hitung yang sudah booking
        $booked = Booking::where('pickup_date', $date)->count();
        
        if($q) {
            $total = $q->quota;
            $sisa = max(0, $total - $booked);
            $statusTeks = $q->is_closed ? 'DITUTUP / LIBUR' : 'DIBUKA';
            
            // Pesan Detail
            return back()->with('swal_info', "Tanggal: $date\nStatus: $statusTeks\nTotal Kuota: $total\nSudah Booking: $booked\nSisa Kuota: $sisa");
        } else {
            // Default logic
            $total = 50;
            $sisa = max(0, 50 - $booked);
            return back()->with('swal_info', "Tanggal: $date\nStatus: DIBUKA (Default)\nTotal Kuota: 50\nSudah Booking: $booked\nSisa Kuota: $sisa");
        }
    }

    public function update(Request $request, $id)
    {
        Booking::findOrFail($id)->update($request->all());
        return back()->with('swal_success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Booking::find($id)->delete();
        return back()->with('swal_success', 'Data berhasil dihapus.');
    }

    public function resetAllData()
    {
        Booking::truncate();
        return back()->with('swal_success', 'DATABASE BERHASIL DIRESET BERSIH!');
    }

    public function toggleStatus($id)
    {
        $booking = Booking::find($id);
        $booking->status = ($booking->status == 'pending') ? 'completed' : 'pending';
        $booking->save();
        return back()->with('swal_success', 'Status berhasil diubah.');
    }

    public function printReport()
    {
        $bookings = Booking::orderBy('pickup_date', 'asc')->get();
        return view('admin.print', compact('bookings'));
    }

    public function cleanData(Request $request)
    {
        $deleted = Booking::where('pickup_date', '<', date('Y-m-d'))->delete();
        Quota::where('date', '<', date('Y-m-d'))->delete();
        return back()->with('swal_success', "Pembersihan selesai! $deleted data lama berhasil dihapus.");
    }
}