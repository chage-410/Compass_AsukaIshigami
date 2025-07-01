<?php

namespace App\Calendars\General;

use Carbon\Carbon;
use Auth;

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

  function render()
  {
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table table-bordered">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th>月</th>';
    $html[] = '<th>火</th>';
    $html[] = '<th>水</th>';
    $html[] = '<th>木</th>';
    $html[] = '<th>金</th>';
    $html[] = '<th class="sat">土</th>';
    $html[] = '<th class="sun">日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
    $weeks = $this->getWeeks();
    foreach ($weeks as $week) {
      $html[] = '<tr class="' . $week->getClassName() . '">';

      $days = $week->getDays();
      foreach ($days as $day) {
        // 空白セルはスキップ（ただし背景グレーを適用）
        if ($day->everyDay() === '') {
          $html[] = '<td class="calendar-td day-blank"></td>';
          continue;
        }

        $weekDay = Carbon::parse($day->everyDay())->dayOfWeek; // 曜日取得（0:日, 6:土）

        $weekClass = '';
        if ($weekDay == 0) {
          $weekClass = 'day-sun';
        } elseif ($weekDay == 6) {
          $weekClass = 'day-sat';
        }

        $startDay = $this->carbon->copy()->format("Y-m-01");
        $toDay = $this->carbon->copy()->format("Y-m-d");
        $isPast = $day->everyDay() < now()->format('Y-m-d');
        $isReserved = in_array($day->everyDay(), $day->authReserveDay());

        if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
          $html[] = '<td class="calendar-td past-day ' . $weekClass . '">';
        } else {
          $html[] = '<td class="calendar-td ' . $day->getClassName() . ' ' . $weekClass . '">';
        }
        $html[] = $day->render();

        if ($isPast) {
          if ($isReserved) {
            $reservePart = $day->authReserveDate($day->everyDay())->first()->setting_part;
            $reserveLabel = match ($reservePart) {
              1 => 'リモ1部',
              2 => 'リモ2部',
              3 => 'リモ3部',
              default => '',
            };
            $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px;">' . $reserveLabel . '</p>';
          } else {
            $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px;">受付終了</p>';
          }
        } else {
          if ($isReserved) {
            // キャンセルボタン（予約済み）
            $reservePart = $day->authReserveDate($day->everyDay())->first()->setting_part;
            $reserveLabel = match ($reservePart) {
              1 => 'リモ1部',
              2 => 'リモ2部',
              3 => 'リモ3部',
              default => '',
            };
            $html[] = '
            <button type="button" class="btn btn-danger p-0 w-75"
              data-toggle="modal"
              data-target="#cancelModal"
              data-date="' . $day->everyDay() . '"
              data-part="' . $reservePart . '">
              ' . $reserveLabel . '
            </button>
            ';
          } else {
            if ($day->everyDay() > now()->format('Y-m-d')) {
              $html[] = $day->selectPart($day->everyDay()); // ← 明日以降のみ表示
            } else {
              $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px;">受付終了</p>'; // ← 今日含む過去
            }
          }
        }
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">' . csrf_field() . '</form>';
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">' . csrf_field() . '</form>';

    return implode('', $html);
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
