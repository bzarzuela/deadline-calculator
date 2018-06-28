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
    protected $tatInHours = null;

    protected $days;

    public function __construct()
    {
        $this->holidays = new Collection();

        $this->operatingHours('00:00:00', '23:59:59');
    }

    public function startFrom($timestamp)
    {
        $this->startFrom = Carbon::parse($timestamp);

        return $this;
    }

    public function tatInDays($days)
    {
        $this->tatInHours = $days * 24;

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

        for ($i = 0; $i < $this->tatInHours; $i++) {
            $deadline->addHour();

            while (
                $this->shouldBypassWeekend($deadline) or
                $this->isHoliday($deadline) or
                $this->isBeyondOperatingHours($deadline)
            ) {
                $deadline->addHour();
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

    public function operatingHours($start, $end)
    {
        $start = Carbon::parse($start);
        $end = Carbon::parse($end);

        $this->sunday($start, $end);
        $this->monday($start, $end);
        $this->tuesday($start, $end);
        $this->wednesday($start, $end);
        $this->thursday($start, $end);
        $this->friday($start, $end);
        $this->saturday($start, $end);

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

    protected function isBeyondOperatingHours(Carbon $deadline)
    {
        $start = $this->days[$deadline->dayOfWeek]['start']->format('H:i:s');
        $end = $this->days[$deadline->dayOfWeek]['end']->format('H:i:s');

        $deadline = $deadline->format('H:i:s');

        if (($deadline >= $end) or ($deadline < $start)) {
            return true;
        }

        return false;
    }

    public function sunday($start, $end)
    {
        $this->setDay($start, $end, Carbon::SUNDAY);

        return $this;
    }

    public function monday($start, $end)
    {
        $this->setDay($start, $end, Carbon::MONDAY);

        return $this;
    }

    public function tuesday($start, $end)
    {
        $this->setDay($start, $end, Carbon::TUESDAY);

        return $this;
    }

    public function wednesday($start, $end)
    {
        $this->setDay($start, $end, Carbon::WEDNESDAY);

        return $this;
    }

    public function thursday($start, $end)
    {
        $this->setDay($start, $end, Carbon::THURSDAY);

        return $this;
    }

    public function friday($start, $end)
    {
        $this->setDay($start, $end, Carbon::FRIDAY);

        return $this;
    }

    public function saturday($start, $end)
    {
        $this->setDay($start, $end, Carbon::SATURDAY);

        return $this;
    }

    protected function shouldBypassWeekend(Carbon $deadline)
    {
        return $this->noWeekends and $deadline->isWeekend();
    }

    protected function setDay($start, $end, $day)
    {
        if (is_string($start)) {
            $start = Carbon::parse($start);
        }

        if (is_string($end)) {
            $end = Carbon::parse($end);
        }

        $this->days[$day] = [
            'start' => $start,
            'end' => $end,
        ];
    }

}
