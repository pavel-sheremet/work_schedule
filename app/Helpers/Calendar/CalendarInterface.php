<?php


namespace App\Helpers\Calendar;


use Carbon\Carbon;
use Illuminate\Support\Collection;

interface CalendarInterface
{
    public function __construct(Carbon $start, Carbon $end);
    public function getStartDate() : Carbon;
    public function getEndDate() : Carbon;
    public function getNotWorkingDates() : Collection;
    public function getWorkingDates() : Collection;
}
