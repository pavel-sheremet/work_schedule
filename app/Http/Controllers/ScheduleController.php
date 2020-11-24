<?php

namespace App\Http\Controllers;

use App\Helpers\Schedule\Schedule;
use App\Helpers\Calendar\XmlCalendarApi\XmlCalendarApi;
use App\Http\Requests\ScheduleRequest;
use App\Http\Resources\ScheduleResource;
use Carbon\Carbon;
use App\Models\User;

class ScheduleController extends Controller
{
    public function index(ScheduleRequest $request)
    {
        $calendar = new XmlCalendarApi(
            Carbon::parse($request->get('startDate')),
            Carbon::parse($request->get('endDate'))
        );

        $user = User::findOrFail($request->get('userId'));
        $schedule = new Schedule($calendar, $user);

        return new ScheduleResource($schedule->getSchedule($request->has('vacation')));
    }
}
