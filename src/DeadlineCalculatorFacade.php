<?php

namespace Bzarzuela\DeadlineCalculator;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Bzarzuela/DeadlineCalculator\SkeletonClass
 */
class DeadlineCalculatorFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'deadline-calculator';
    }
}
