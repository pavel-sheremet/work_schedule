<?php


namespace App\Helpers\Calendar;


use Carbon\Carbon;

class AbstractCalendar implements CalendarInterface
{
    protected $starDate = null;
    protected $endDate = null;

    public function __construct(Carbon $starDate, Carbon $endDate)
    {
        $this->starDate = $starDate;
        $this->endDate = $endDate;
    }

    public function getStartDate()
    {
        return $this->starDate;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function getWorkingDates()
    {
    }

    public function getNotWorkingDates()
    {
    }
}
