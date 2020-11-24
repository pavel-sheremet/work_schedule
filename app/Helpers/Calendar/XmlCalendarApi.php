<?php


namespace App\Helpers\Calendar;


use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;

class XmlCalendarApi extends AbstractCalendar
{
    protected $workingDates = null;
    protected $notWorkingDates = null;

    public function getNotWorkingDates()
    {
        if (!is_null($this->notWorkingDates)) return $this->notWorkingDates;

        $startYear = $tempYear = $this->starDate->year;
        $endYear = $this->endDate->year;
        $vacationDates = [];

        while ($tempYear <= $endYear)
        {

            $response = Http::get('http://xmlcalendar.ru/data/ru/' . $startYear . '/calendar.json');
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

        return $this->notWorkingDates;
    }


    public function getWorkingDates()
    {
        if (!is_null($this->workingDates)) return $this->workingDates;

        $period = CarbonPeriod::create($this->starDate , $this->endDate);
        $notWorkingPeriod = $this->getNotWorkingDates();
        $this->workingDates = $this->getPeriodsDiff($period, $notWorkingPeriod);

        return $this->workingDates;
    }

    public function getPeriodsDiff ($period1, $period2)
    {
        return collect($period1)->diff(collect($period2))->values();
    }
}
