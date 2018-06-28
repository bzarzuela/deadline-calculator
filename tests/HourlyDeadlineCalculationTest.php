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
}
