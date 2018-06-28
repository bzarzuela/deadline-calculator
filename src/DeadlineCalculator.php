<?php

namespace Bzarzuela\DeadlineCalculator;

use Illuminate\Support\Collection;

class DeadlineCalculator
{
    protected $startFrom = null;
    protected $tat = null;
    protected $holidays;

    /**
     * DeadlineCalculator constructor.
     */
    public function __construct()
    {
        $this->holidays = new Collection();
    }


    public function startFrom($timestamp)
    {
        $this->startFrom = strtotime($timestamp);

        return $this;
    }

    public function tatInDays($days)
    {
        $this->tat = $days * 86400;

        return $this;
    }

    public function deadline()
    {
        $deadline = $this->startFrom + $this->tat;

        foreach ($this->holidays as $holiday) {
            $holiday_timestamp = strtotime($holiday);
            if (($holiday_timestamp >= $this->startFrom) and ($holiday_timestamp <= $deadline)) {
                $deadline += 86400;
            }
        }

        return date('Y-m-d H:i:s', $deadline);
    }

    public function addHoliday($holiday)
    {
        $this->holidays->push($holiday);
    }


}
