<?php

namespace Bzarzuela\DeadlineCalculator\Tests;

use Bzarzuela\DeadlineCalculator\DeadlineCalculator;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class OperatingHoursTest extends TestCase
{
    /** @test */
    function operating_hours_are_taken_into_consideration_when_computing_for_deadlines()
    {
        $calculator = new DeadlineCalculator();

        $calculator->startFrom('2018-06-28 16:30:00')
            ->operatingHours('09:00:00', '17:00:00')
            ->tatInHours(1);

        $this->assertEquals('2018-06-29 09:30:00', $calculator->deadline());
    }

    /** @test */
    function deadlines_on_a_friday_afternoon_will_be_started_on_monday()
    {
        $calculator = new DeadlineCalculator();

        $calculator->startFrom('2018-06-29 16:59:59')
            ->operatingHours('09:00:00', '17:00:00')
            ->noWeekends()
            ->tatInHours(1);

        $this->assertEquals('2018-07-02 09:59:59', $calculator->deadline());
    }

    /** @test */
    function deadlines_on_a_friday_afternoon_and_monday_holiday_will_be_started_on_tuesday()
    {
        $calculator = new DeadlineCalculator();

        $calculator->startFrom('2018-06-29 16:59:59')
            ->operatingHours('09:00:00', '17:00:00')
            ->noWeekends()
            ->addHoliday('2018-07-02')
            ->tatInHours(1);

        $this->assertEquals('2018-07-03 09:59:59', $calculator->deadline());
    }

    /** @test */
    function half_days_on_saturdays_can_be_supported()
    {
        $calculator = new DeadlineCalculator();

        $calculator->startFrom('2018-06-29 16:00:00')
            ->operatingHours('09:00:00', '17:00:00')
            ->saturday('09:00:00', '13:00:00')
            ->noSunday()
            ->tatInHours(8);

        $this->assertEquals('2018-07-02 12:00:00', $calculator->deadline());
    }
}