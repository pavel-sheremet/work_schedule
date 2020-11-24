<?php

namespace Database\Seeders;

use App\Models\VacationSchedule;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class VacationScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VacationSchedule::insert([
            [
                'user_id' => 1,
                'start_date' => Carbon::createFromDate(2018, 1, 11),
                'end_date' => Carbon::createFromDate(2018, 1, 25),
            ],
            [
                'user_id' => 1,
                'start_date' => Carbon::createFromDate(2018, 2, 1),
                'end_date' => Carbon::createFromDate(2018, 2, 15),
            ],
            [
                'user_id' => 2,
                'start_date' => Carbon::createFromDate(2018, 2, 1),
                'end_date' => Carbon::createFromDate(2018, 3, 1),
            ]
        ]);
    }
}
