<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::create(['key' => 'bg_type', 'value' => 'image']); // image atau video
        Setting::create(['key' => 'bg_file', 'value' => 'default.jpg']); // Nama file
    }
}