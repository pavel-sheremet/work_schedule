<?php

namespace Tests\Feature;

use App\Helpers\Calendar\XmlCalendarApi\XmlCalendarApi;
use Carbon\Carbon;
use Tests\TestCase;

class XmlCalendarApiTest extends TestCase
{
    public function testFetWorkingDates()
    {
        $calendar = new XmlCalendarApi(
            Carbon::parse('2018-01-01'),
            Carbon::parse('2018-01-30')
        );

        $dates = $calendar->getWorkingDates();

        foreach ($dates as $date)
        {
            if (!($date instanceof Carbon)) $this->assertTrue(false);
        }

        $this->assertTrue(true);
    }

    public function testBasicGetNotWorkingDates()
    {
        $calendar = new XmlCalendarApi(
            Carbon::parse('2018-01-01'),
            Carbon::parse('2018-01-30')
        );

        $dates = $calendar->getNotWorkingDates();

        foreach ($dates as $date)
        {
            if (!($date instanceof Carbon)) $this->assertTrue(false);
        }

        $this->assertTrue(true);
    }
}
