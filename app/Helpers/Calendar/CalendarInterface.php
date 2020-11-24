<?php


namespace App\Helpers\Calendar;


use Carbon\Carbon;

interface CalendarInterface
{
    public function __construct(Carbon $start, Carbon $end);
    public function getStartDate();
    public function getEndDate();
    public function getNotWorkingDates();
    public function getWorkingDates();
}
