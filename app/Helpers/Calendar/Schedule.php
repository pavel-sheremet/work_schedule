<?php


namespace App\Helpers\Calendar;


use App\Models\SpecificVacation;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Schedule
{
    private $calendar;
    private $user;
    private $vacationDates = null;
    private $workingDates = null;

    public function __construct(AbstractCalendar $calendar, User $user)
    {
        $this->calendar = $calendar;
        $this->user = $user;
    }

    public function getVacationDates()
    {
        if (!is_null($this->vacationDates)) return $this->vacationDates;

        $period = [];

        foreach ($this->user->vacationSchedules as $vacationSchedule)
        {
            $period = array_merge($period, CarbonPeriod::create($vacationSchedule->start_date, $vacationSchedule->end_date)->toArray());
        }

        $this->vacationDates = collect($period);

        return $this->vacationDates;
    }


    public function getWorkingDates()
    {
        if (!is_null($this->workingDates)) return $this->workingDates;

        $this->workingDates = collect($this->calendar->getWorkingDates())->diff($this->getVacationDates());

        return $this->workingDates;
    }

    public function getSpecificDates()
    {
        return SpecificVacation::whereBetween(
            'date_time_start',
            [
                $this->calendar->getStartDate(),
                $this->calendar->getEndDate()
            ]
        )->get()->map(function ($item) {
            return [
                ['time' => Carbon::parse($item->date_time_start), 'start_break' => true],
                ['time' => Carbon::parse($item->date_time_end), 'start_break' => false],
            ];
        });
    }

    public function getSchedule($reverse = false)
    {
        $regularWorkSchedule = $this->user->regularWorkSchedule;
        $specificVacations = $this->getSpecificDates();

        $workingDates = $this->getWorkingDates()->map(function ($date) use ($specificVacations, $regularWorkSchedule, $reverse) {
            $workingYear = $date->year;
            $workingMonth = $date->month;
            $workingDay = $date->day;

            $regularStartTime = Carbon::parse($regularWorkSchedule->start_time);
            $regularBreakStartTime = Carbon::parse($regularWorkSchedule->break_start_time);
            $regularBreakEndTime = Carbon::parse($regularWorkSchedule->break_end_time);
            $regularEndTime = Carbon::parse($regularWorkSchedule->end_time);

            $workDayStartTime = Carbon::create($workingYear, $workingMonth, $workingDay, $regularStartTime->hour, $regularStartTime->minute, $regularStartTime->second);
            $workDayBreakStartTime = Carbon::create($workingYear, $workingMonth, $workingDay, $regularBreakStartTime->hour, $regularBreakStartTime->minute, $regularBreakStartTime->second);
            $workDayBreakEndTime = Carbon::create($workingYear, $workingMonth, $workingDay, $regularBreakEndTime->hour, $regularBreakEndTime->minute, $regularBreakEndTime->second);
            $workDayEndTime = Carbon::create($workingYear, $workingMonth, $workingDay, $regularEndTime->hour, $regularEndTime->minute, $regularEndTime->second);

            $timePeriods = [
                ['time' => $workDayStartTime, 'start_break' => false],
                ['time' => $workDayBreakStartTime, 'start_break' => true],
                ['time' => $workDayBreakEndTime, 'start_break' => false],
                ['time' => $workDayEndTime, 'start_break' => true, 'end_time' => true],
            ];

            foreach ($specificVacations as $specificVacation)
            {
                if ($specificVacation[0]['time']->between($date, $workDayEndTime))
                {
                    $timePeriods = array_merge($timePeriods, $specificVacation);
                }
            }

            return $this->getDaySchedule($date, $timePeriods, $reverse);
        })->values();

        return $workingDates;
    }

    public function getDaySchedule($date, $timePeriods, $reverse = false)
    {
        uasort($timePeriods, function ($a, $b) {
            if ($a['time'] == $b['time'])
            {
                return ($a['start_break'] > $b['start_break']) ? -1 : 1;
            }

            return ($a['time'] < $b['time']) ? -1 : 1;
        });

        $timeRanges = [
            'date' => $date->format('Y-m-d'),
            'timeRanges' => []
        ];
        $range = [];
        $t = 0;
        $f = null;

        foreach ($timePeriods as $period)
        {
            $isEndTime = isset($period['end_time']) && (bool)$period['end_time'];
            $t = (!(bool)$period['start_break']) ? ++$t : --$t;
            $f = !(bool)$period['start_break'];

            if ($reverse)
            {
                if ($t == 0 && !(bool)$f && !$isEndTime)
                {
                    $range['start'] = $period['time']->format('Hi');
                }

                if (($t == 1 && (bool)$f || $isEndTime) && isset($range['start']))
                {
                    $range['end'] = $period['time']->format('Hi');
                    $timeRanges['timeRanges'][] = $range;
                    $range = [];
                }
            }
            else
            {
                if ($t == 1 && (bool)$f)
                {
                    $range['start'] = $period['time']->format('Hi');
                }

                if ($t == 0 && !(bool)$f)
                {
                    $range['end'] = $period['time']->format('Hi');
                    $timeRanges['timeRanges'][] = $range;
                    $range = [];
                }
            }
        }

        return $timeRanges;
    }
}
