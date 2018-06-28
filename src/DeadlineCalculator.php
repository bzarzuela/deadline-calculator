<?php

namespace Bzarzuela\DeadlineCalculator;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class DeadlineCalculator
{
    /** @var Carbon */
    protected $startFrom;

    protected $tatInDays = null;
    protected $holidays;
    protected $noWeekends = false;

    /**
     * DeadlineCalculator constructor.
     */
    public function __construct()
    {
        $this->holidays = new Collection();
    }


    public function startFrom($timestamp)
    {
        $this->startFrom = Carbon::parse($timestamp);

        return $this;
    }

    public function tatInDays($days)
    {
        $this->tatInDays = $days;

        return $this;
    }

    public function deadline()
    {
        $startFrom = $this->startFrom->timestamp;

        if ($this->noWeekends === false) {
            $deadline = $this->startFrom->addDays($this->tatInDays)->timestamp;
        } else {
            $deadline = $this->startFrom->addWeekdays($this->tatInDays)->timestamp;
        }

        foreach ($this->holidays as $holiday) {
            $holiday_timestamp = strtotime($holiday);
            if (($holiday_timestamp >= $startFrom) and ($holiday_timestamp <= $deadline)) {
                $deadline += 86400;
            }
        }

        return date('Y-m-d H:i:s', $deadline);
    }

    public function addHoliday($holiday)
    {
        $this->holidays->push($holiday);

        return $this;
    }

    public function noWeekends($value = true)
    {
        $this->noWeekends = $value;

        return $this;
    }


}
