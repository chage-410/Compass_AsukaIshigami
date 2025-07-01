<?php

namespace App\Calendars\General;

use App\Models\Calendars\ReserveSettings;
use Carbon\Carbon;
use Auth;

class CalendarWeekDay
{
  protected $carbon;

  function __construct($date)
  {
    $this->carbon = Carbon::parse($date)->setTimezone('Asia/Tokyo')->startOfDay();
  }

  function getClassName()
  {
    return "day-" . strtolower($this->carbon->format("D"));
  }

  function pastClassName()
  {
    return;
  }

  /**
   * @return
   */

  function render()
  {
    return '<span class="calendar-date">' . $this->carbon->format("j") .  '日</span>';
  }

  function selectPart($ymd)
  {
    $one_part_frame = ReserveSettings::where('setting_reserve', $ymd)->where('setting_part', '1')->value('limit_users') ?? 20;
    $two_part_frame = ReserveSettings::where('setting_reserve', $ymd)->where('setting_part', '2')->value('limit_users') ?? 20;
    $three_part_frame = ReserveSettings::where('setting_reserve', $ymd)->where('setting_part', '3')->value('limit_users') ?? 20;

    $html = [];
    $html[] = '<select name="getPart[]" class="border-primary" style="width:70px; border-radius:5px;" form="reserveParts">';
    $html[] = '<option value="" selected></option>';
    $html[] = $one_part_frame == 0
      ? '<option value="1" disabled>リモ1部(残り0枠)</option>'
      : '<option value="1">リモ1部(残り' . $one_part_frame . '枠)</option>';
    $html[] = $two_part_frame == 0
      ? '<option value="2" disabled>リモ2部(残り0枠)</option>'
      : '<option value="2">リモ2部(残り' . $two_part_frame . '枠)</option>';
    $html[] = $three_part_frame == 0
      ? '<option value="3" disabled>リモ3部(残り0枠)</option>'
      : '<option value="3">リモ3部(残り' . $three_part_frame . '枠)</option>';
    $html[] = '</select>';


    $html[] = '<input type="hidden" name="getData[]" value="' . $ymd . '" form="reserveParts">';

    return implode('', $html);
  }

  function getDate()
  {
    return '<input type="hidden" value="' . $this->carbon->format('Y-m-d') . '" name="getData[]" form="reserveParts">';
  }

  function everyDay()
  {
    return $this->carbon->format('Y-m-d');
  }

  function authReserveDay()
  {
    return Auth::user()->reserveSettings->pluck('setting_reserve')->toArray();
  }

  function authReserveDate($reserveDate)
  {
    return Auth::user()->reserveSettings->where('setting_reserve', $reserveDate);
  }
}
