<?php

namespace Bzarzuela\DeadlineCalculator;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class DeadlineCalculator
{
    /** @var Collection */
    protected $holidays;

    /** @var Carbon */
    protected $startFrom;

    protected $noWeekends = false;
    protected $tatInDays = null;
    protected $tatInHours = null;

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

    public function tatInHours($hours)
    {
        $this->tatInHours = $hours;

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

                if ($this->noWeekends === false) {
                    $deadline = Carbon::createFromTimestamp($deadline)->addDay()->timestamp;
                } else {
                    $deadline = Carbon::createFromTimestamp($deadline)->addWeekday()->timestamp;
                }
            }
        }

        if ($this->tatInHours !== null) {
            $deadline = Carbon::createFromTimestamp($deadline)->addHours($this->tatInHours);

            if ($this->noWeekends === true) {
                
                while ($deadline->isWeekend()) {
                    $deadline->addDay();

                    if ($this->isHoliday($deadline)) {
                        $deadline->addDay();
                    }
                }
            }

            $deadline = $deadline->timestamp;
        }

        return date('Y-m-d H:i:s', $deadline);
    }

    public function addHoliday($holiday)
    {
        $this->holidays[$holiday] = $holiday;

        return $this;
    }

    public function noWeekends($value = true)
    {
        $this->noWeekends = $value;

        return $this;
    }

    protected function isHoliday(Carbon $day)
    {
        return $this->holidays->has($day->format('Y-m-d'));
    }


}
