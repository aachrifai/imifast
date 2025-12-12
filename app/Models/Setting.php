<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    // Tambahkan baris ini agar kolom 'key' dan 'value' bisa diisi
    protected $fillable = ['key', 'value'];
}