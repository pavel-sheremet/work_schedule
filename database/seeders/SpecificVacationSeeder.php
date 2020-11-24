<?php

namespace Database\Seeders;

use App\Models\SpecificVacation;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SpecificVacationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SpecificVacation::create([
            'date_time_start' => Carbon::create(2018, 1, 10, 15),
            'date_time_end' => Carbon::create(2018, 1, 11),
        ]);
    }
}
