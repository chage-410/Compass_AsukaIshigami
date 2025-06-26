<?php

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
    public function show()
    {
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.general.calendar', compact('calendar'));
    }

    public function reserve(Request $request)
    {

        DB::beginTransaction();
        try {
            $getParts = $request->getPart;  // 選択した部（1,2,3など）
            $getDates = $request->getData;  // 対応する日付

            foreach ($getParts as $index => $part) {
                if (!empty($part)) {
                    $date = $getDates[$index];

                    $reserve = ReserveSettings::where('setting_reserve', $date)
                        ->where('setting_part', $part)
                        ->first();

                    if ($reserve && $reserve->limit_users > 0) {
                        if (!$reserve->users->contains(Auth::id())) {
                            $reserve->decrement('limit_users');
                            $reserve->users()->attach(Auth::id());
                        }
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors('予約に失敗しました: ' . $e->getMessage());
        }

        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            Log::debug('キャンセル対象', [
                '日付' => $request->delete_date,
                'ユーザーID' => Auth::id(),
            ]);

            $setting = ReserveSettings::where('setting_reserve', $request->delete_date)
                ->whereHas('users', function ($query) {
                    $query->where('users.id', Auth::id());
                })
                ->first();

            Log::debug('見つかった設定', ['setting' => $setting]);

            if ($setting) {
                $setting->increment('limit_users');
                $setting->users()->detach(Auth::id());
            } else {
                Log::warning('予約設定が見つかりませんでした');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('キャンセルエラー: ' . $e->getMessage());
            return back()->withErrors('キャンセルに失敗しました: ' . $e->getMessage());
        }

        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }
}
