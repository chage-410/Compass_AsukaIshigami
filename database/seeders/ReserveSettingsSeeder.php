<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Calendars\ReserveSettings;
use Carbon\Carbon;

class ReserveSettingsSeeder extends Seeder
{
    public function run()
    {
        // 例: 今日から30日分 × 各3部のデータを追加
        $today = Carbon::today();
        for ($i = 0; $i < 30; $i++) {
            $date = $today->copy()->addDays($i)->format('Y-m-d');

            for ($part = 1; $part <= 3; $part++) {
                ReserveSettings::create([
                    'setting_reserve' => $date,
                    'setting_part' => $part,
                    'limit_users' => 20,
                ]);
            }
        }
    }
}
