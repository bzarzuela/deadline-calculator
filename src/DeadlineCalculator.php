<?php

namespace Bzarzuela\DeadlineCalculator;

class DeadlineCalculator
{
    protected $startFrom = null;
    protected $tat = null;

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
        return date('Y-m-d H:i:s', $this->startFrom + $this->tat);
    }


}
