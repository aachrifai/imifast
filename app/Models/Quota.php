<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Quota extends Model
{
    protected $fillable = ['date', 'quota', 'is_closed'];
}