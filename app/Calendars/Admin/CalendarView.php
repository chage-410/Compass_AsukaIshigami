<?php

namespace App\Calendars\Admin;

use Carbon\Carbon;
use App\Models\Calendars\ReserveSettings;

class CalendarView
{
  private $carbon;

  function __construct($date)
  {
    $this->carbon = new Carbon($date);
  }

  public function getTitle()
  {
    return $this->carbon->format('Y年n月');
  }

  public function render()
  {
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table table-bordered m-auto border adjust-table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th class="border">月</th>';
    $html[] = '<th class="border">火</th>';
    $html[] = '<th class="border">水</th>';
    $html[] = '<th class="border">木</th>';
    $html[] = '<th class="border">金</th>';
    $html[] = '<th class="border sat">土</th>';
    $html[] = '<th class="border sun">日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
    $weeks = $this->getWeeks();

    foreach ($weeks as $week) {
      $html[] = '<tr class="' . $week->getClassName() . '">';
      $days = $week->getDays();

      foreach ($days as $day) {
        $startDay = $this->carbon->format("Y-m-01");
        $toDay = $this->carbon->format("Y-m-d");

        $weekDay = Carbon::parse($day->everyDay())->dayOfWeek; // 曜日取得（0:日, 6:土）

        $weekClass = '';
        if ($weekDay == 0) {
          $weekClass = 'day-sun';
        } elseif ($weekDay == 6) {
          $weekClass = 'day-sat';
        }

        if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
          $html[] = '<td class="calendar-td past-day ' . $weekClass . '">';
        } else {
          $html[] = '<td class="calendar-td ' . $day->getClassName() . ' ' . $weekClass . '">';
        }

        $html[] = $day->render();
        $html[] = '<div class="adjust-area">';

        if ($day->everyDay()) {
          foreach ([1, 2, 3] as $part) {
            $reserve = \App\Models\Calendars\ReserveSettings::with('users')
              ->where('setting_reserve', $day->everyDay())
              ->where('setting_part', $part)
              ->first();
            $count = $reserve ? $reserve->users->count() : 0;
            $url = route('calendar.admin.detail', ['date' => $day->everyDay(), 'part' => $part]);

            $html[] = '<div class="d-flex justify-content-between align-items-center px-1" style="font-size: 12px;">';
            $html[] = '<a href="' . $url . '" class="text-primary">' . $part . '部</a>';
            $html[] = '<span>' . $count . '</span>';
            $html[] = '</div>';
          }
        }
        $html[] = '</div>';
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="' . route('calendar.admin.update') . '" method="post" id="reserveSetting">' . csrf_field() . '</form>';
    return implode("", $html);
  }
  protected function getWeeks()
  {
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();
    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    while ($tmpDay->lte($lastDay)) {
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
