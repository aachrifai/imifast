<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    // Pastikan semua kolom ini ada agar bisa disimpan
    protected $fillable = [
        'booking_code',
        'service_type', 
        'passport_name', 
        'collector_name', 
        'phone', 
        'pickup_date', 
        'status'
    ];
}