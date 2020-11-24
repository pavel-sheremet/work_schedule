<?php

namespace Tests\Unit;

use App\Helpers\Calendar\XmlCalendarApi\XmlCalendarApi;
use App\Helpers\Schedule\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;


class ScheduleTest extends TestCase
{
    public function testGetVacationDates()
    {
        $calendar = new XmlCalendarApi(
            Carbon::parse('2018-01-01'),
            Carbon::parse('2018-01-30')
        );

        $user = User::find(1);
        $schedule = new Schedule($calendar, $user);
        $dates = $schedule->getVacationDates();

        foreach ($dates as $date)
        {
            if (!($date instanceof Carbon)) $this->assertTrue(false);
        }

        $this->assertTrue(true);
    }

    public function testGetWorkingDates()
    {
        $calendar = new XmlCalendarApi(
            Carbon::parse('2018-01-01'),
            Carbon::parse('2018-01-30')
        );

        $user = User::find(1);
        $schedule = new Schedule($calendar, $user);
        $dates = $schedule->getWorkingDates();

        foreach ($dates as $date)
        {
            if (!($date instanceof Carbon)) $this->assertTrue(false);
        }

        $this->assertTrue(true);
    }

    public function testGetSpecificDates()
    {
        $calendar = new XmlCalendarApi(
            Carbon::parse('2018-01-01'),
            Carbon::parse('2018-01-30')
        );

        $user = User::find(1);
        $schedule = new Schedule($calendar, $user);
        $periods = $schedule->getSpecificDates();

        foreach ($periods as $dates)
        {
            if (!is_array($dates)) $this->assertTrue(false);

            foreach ($dates as $date)
            {
                if (!isset($date['time']) || !isset($date['start_break'])) $this->assertTrue(false);
                if (!is_bool($date['start_break'])) $this->assertTrue(false);
                if (!($date['time'] instanceof Carbon)) $this->assertTrue(false);
            }

            $this->assertTrue(true);
        }
    }
}
