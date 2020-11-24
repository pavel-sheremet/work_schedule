<?php


namespace App\Helpers\Calendar\XmlCalendarApi;


use App\Helpers\Calendar\CalendarInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class XmlCalendarApi implements CalendarInterface
{
    protected $workingDates = null;
    protected $notWorkingDates = null;
    protected $starDate = null;
    protected $endDate = null;

    public function __construct(Carbon $starDate, Carbon $endDate)
    {
        $this->starDate = $starDate;
        $this->endDate = $endDate;
        $this->setNotWorkingDates();
        $this->setWorkingDates();
    }

    public function getStartDate() : Carbon
    {
        return $this->starDate;
    }

    public function getEndDate() : Carbon
    {
        return $this->endDate;
    }

    public function getNotWorkingDates() : Collection
    {
        return $this->notWorkingDates;
    }

    public function getWorkingDates() : Collection
    {
        return $this->workingDates;
    }

    public function getPeriodsDiff ($period1, $period2)
    {
        return collect($period1)->diff(collect($period2))->values();
    }

    protected function setNotWorkingDates()
    {
        $startYear = $tempYear = $this->starDate->year;
        $endYear = $this->endDate->year;
        $vacationDates = [];

        while ($tempYear <= $endYear)
        {

            $response = Http::get("http://xmlcalendar.ru/data/ru/$startYear/calendar.json");
            $dates = $response->json();

            foreach ($dates['months'] as $month)
            {
                $month['days'] = array_map(function ($item) {
                    return (int)$item;
                }, explode(',', $month['days']));

                foreach ($month['days'] as $day)
                {
                    $vacationDates[] = Carbon::create($tempYear, $month['month'], (int)$day);
                }
            }

            $tempYear++;
        }

        $this->notWorkingDates = collect($vacationDates);
    }

    protected function setWorkingDates()
    {
        $period = CarbonPeriod::create($this->starDate , $this->endDate);
        $notWorkingPeriod = $this->getNotWorkingDates();
        $this->workingDates = $this->getPeriodsDiff($period, $notWorkingPeriod);
    }


}
