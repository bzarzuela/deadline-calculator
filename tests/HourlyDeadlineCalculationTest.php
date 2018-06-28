<?php

namespace Bzarzuela\DeadlineCalculator\Tests;

use Bzarzuela\DeadlineCalculator\DeadlineCalculator;
use PHPUnit\Framework\TestCase;

class HourlyDeadlineCalculationTest extends TestCase
{
    /** @test */
    function deadlines_can_be_set_in_hours()
    {
        $calc = new DeadlineCalculator();

        $calc->startFrom('2018-06-28 14:44:00')
            ->tatInHours(3);

        $this->assertEquals('2018-06-28 17:44:00', $calc->deadline());
    }

    /** @test */
    function deadlines_can_span_weekends()
    {
        $calc = new DeadlineCalculator();

        $calc->startFrom('2018-06-29 14:00:00')
            ->noWeekends()
            ->tatInHours(24);

        $this->assertEquals('2018-07-02 14:00:00', $calc->deadline());
    }

    /** @test */
    function deadlines_take_holidays_in_consideration()
    {
        $calc = new DeadlineCalculator();

        $calc->startFrom('2018-06-29 14:00:00')
            ->noWeekends()
            ->addHoliday('2018-07-02')
            ->tatInHours(24);

        $this->assertEquals('2018-07-03 14:00:00', $calc->deadline());
    }

    /** @test */
    function hourly_deadlines_can_span_multiple_weekends_and_holidays()
    {
        $calculator = new DeadlineCalculator();

        $calculator->startFrom('2018-12-17 00:00:00')
            ->noWeekends()
            ->addHoliday('2018-12-24')
            ->addHoliday('2018-12-25')
            ->addHoliday('2018-12-31')
            ->addHoliday('2019-01-01')
            ->tatInHours(14 * 24);

        $this->assertEquals('2019-01-10 00:00:00', $calculator->deadline());
    }
}
