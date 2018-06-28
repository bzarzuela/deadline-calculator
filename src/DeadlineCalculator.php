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
        $deadline = $this->startFrom;

        if ($this->tatInDays) {
            for ($i = 0; $i < $this->tatInDays; $i++) {
                $deadline = $this->addDay($deadline);

                while ($this->isHoliday($deadline)) {
                    $deadline = $this->addDay($deadline);
                }
            }
        }

        if ($this->tatInHours) {
            $deadline->addHours($this->tatInHours);

            if ($this->noWeekends === true) {

                while ($deadline->isWeekend()) {
                    $deadline->addDay();

                    if ($this->isHoliday($deadline)) {
                        $deadline->addDay();
                    }
                }
            }
        }

        return $deadline->format('Y-m-d H:i:s');
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

    protected function addDay(Carbon $date)
    {
        if ($this->noWeekends) {
            return $date->addWeekday();
        }

        return $date->addDay();
    }


}
