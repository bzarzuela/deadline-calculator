<?php

namespace Bzarzuela\DeadlineCalculator\Tests;

use Bzarzuela\DeadlineCalculator\DeadlineCalculator;
use PHPUnit\Framework\TestCase;

class DeadlineCalculationTest extends TestCase
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
    function holidays_can_be_skipped()
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

        $calculator->startFrom('2018-06-28 12:20:00');
        $calculator->tatInDays(3);

        $calculator->addHoliday('2018-06-27');
        $calculator->addHoliday('2018-07-02');

        $this->assertEquals('2018-07-01 12:20:00', $calculator->deadline());
    }
}
