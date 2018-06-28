<?php

namespace Bzarzuela\DeadlineCalculator\Tests;

use Bzarzuela\DeadlineCalculator\DeadlineCalculator;
use PHPUnit\Framework\TestCase;

class DailyDeadlineCalculationTest extends TestCase
{
    /** @test */
    function deadlines_can_be_computed_by_days()
    {
        $calculator = new DeadlineCalculator();
        $calculator->startFrom('2018-06-28 12:20:00');
        $calculator->tatInDays(3);

        $this->assertEquals('2018-07-01 12:20:00', $calculator->deadline());
    }

    /** @test */
    function holidays_will_automatically_adjust_the_deadline()
    {
        $calculator = new DeadlineCalculator();

        $calculator->startFrom('2018-06-28 12:20:00');
        $calculator->tatInDays(3);

        $calculator->addHoliday('2018-06-29');

        $this->assertEquals('2018-07-02 12:20:00', $calculator->deadline());
    }

    /** @test */
    function holidays_that_do_not_affect_the_deadline_are_ignored()
    {
        $calculator = new DeadlineCalculator();

        $calculator->startFrom('2018-06-28 12:20:00')
            ->tatInDays(3)
            ->addHoliday('2018-06-27')
            ->addHoliday('2018-07-02');

        $this->assertEquals('2018-07-01 12:20:00', $calculator->deadline());
    }

    /** @test */
    function weekends_can_be_excluded_from_the_deadline()
    {
        $calculator = new DeadlineCalculator();

        $calculator->startFrom('2018-06-29 12:20:00');
        $calculator->tatInDays(3);
        $calculator->noWeekends();

        $this->assertEquals('2018-07-04 12:20:00', $calculator->deadline());
    }
    
    /** @test */
    function deadlines_can_span_multiple_weekends_and_holidays()
    {
        $calculator = new DeadlineCalculator();

        $calculator->startFrom('2018-12-17 00:00:00')
            ->noWeekends()
            ->addHoliday('2018-12-24')
            ->addHoliday('2018-12-25')
            ->addHoliday('2018-12-31')
            ->addHoliday('2019-01-01')
            ->tatInDays(14);

        $this->assertEquals('2019-01-10 00:00:00', $calculator->deadline());
    }
}
