<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ScheduleApiResultTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testApiWorkingScheduleResult()
    {
        $response = $this->getJson('/schedule/?startDate=2018-01-09&endDate=2019-01-01&userId=1');

        $response->assertJsonStructure([
            [
                'date',
                'timeRanges' => [
                    '*' => ['start', 'end']
                ]
            ]
        ]);
    }

    public function testApiNotWorkingScheduleResult()
    {
        $response = $this->getJson('/schedule/?startDate=2018-01-09&endDate=2019-01-01&userId=1&vacation');

        $response->assertJsonStructure([
            [
                'date',
                'timeRanges' => [
                    '*' => ['start', 'end']
                ]
            ]
        ]);
    }
}
