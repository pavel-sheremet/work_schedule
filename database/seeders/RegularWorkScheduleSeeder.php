<?php

namespace Database\Seeders;

use App\Models\RegularWorkSchedule;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RegularWorkScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RegularWorkSchedule::insert([
            [
                'user_id' => 1,
                'start_time' => Carbon::createFromTime(10, 00),
                'end_time' => Carbon::createFromTime(19, 00),
                'break_start_time' => Carbon::createFromTime(13, 00),
                'break_end_time' => Carbon::createFromTime(14, 00),
            ],
            [
                'user_id' => 2,
                'start_time' => Carbon::createFromTime(9, 00),
                'end_time' => Carbon::createFromTime(18, 00),
                'break_start_time' => Carbon::createFromTime(12, 00),
                'break_end_time' => Carbon::createFromTime(13, 00),
            ],
        ]);
    }
}
