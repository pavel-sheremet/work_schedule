<?php

namespace App\Http\Controllers;

use App\Helpers\Calendar\Schedule;
use App\Helpers\Calendar\XmlCalendarApi;
use App\Http\Requests\ScheduleRequest;
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

        $user = User::find($request->get('userId'));
        $schedule = new Schedule($calendar, $user);

        return response()->json($schedule->getSchedule($request->has('vacation')));
    }
}
