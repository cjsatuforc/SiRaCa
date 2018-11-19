<?php
namespace wcf\system\siraca\date;

use wcf\system\siraca\date\DateUtil;
use wcf\system\WCF;

class Month
{
    private $yearValue;
    private $monthValue;
    private $monthName;

    private $days;
    private $dateTime;

    private function __construct($yearValue, $monthValue)
    {
        $this->yearValue  = intval($yearValue);
        $this->monthValue = intval($monthValue);

        $this->dateTime = DateUtil::getNewDate();
        $this->dateTime->setDate($this->yearValue, $this->monthValue, 1);
        try {
            $this->dateTime->setTime(0, 0, 0, 0);
        } catch (\Exception $e) {
            $this->dateTime->setTime(0, 0, 0);
        }
    }

    public static function getMonth($yearValue, $monthValue, $ignoreTimestampLimits = false)
    {
        if (!$ignoreTimestampLimits && ($yearValue < DateUtil::MIN_YEAR || $yearValue > DateUtil::MAX_YEAR)
            || $monthValue < 1 || $monthValue > 12) {
            return null;
        }

        return new Month($yearValue, $monthValue);
    }

    public static function getCurrentMonth()
    {
        $date  = DateUtil::getNewDate();
        $year  = $date->format('Y');
        $month = $date->format('n');

        return new Month($year, $month);
    }

    public function isCurrentMonth()
    {
        return $this->equals(self::getCurrentMonth());
    }

    public function getYearValue()
    {
        return $this->yearValue;
    }

    public function getMonthValue()
    {
        return $this->monthValue;
    }

    public function getMonthName()
    {
        if (!$this->monthName) {
            $this->monthName = strtolower($this->dateTime->format("F"));
        }
        return $this->monthName;
    }

    public function getDays()
    {
        if ($this->days == null) {
            $dayCount = $this->dateTime->format('t');

            $this->days = [];
            for ($i = 1; $i <= $dayCount; $i++) {
                $this->days[$i] = new Day($this, $i);
            }
        }

        return $this->days;
    }

    public function getFirstDay()
    {
        return $this->getDays()[1];
    }

    public function getLastDay()
    {
        $dayCount = intval($this->dateTime->format('t'));
        return $this->getDays()[$dayCount];
    }

    public function getDay($dayValue)
    {
        if ($dayValue < 1 || $dayValue > $this->getLastDay()->getDayValue()) {
            return null;
        }

        return $this->getDays()[$dayValue];
    }

    /**
     * 0 -> last day
     * -1 -> previous one...
     */
    public function getDayFromEnd($fromEnd)
    {
        if ($fromEnd > 0 || $fromEnd <= -$this->getLastDay()->getDayValue()) {
            return null;
        }

        return $this->getDays()[$this->getLastDay()->getDayValue() + $fromEnd];
    }

    public function getNextDay($day)
    {
        if (!$this->contains($day)) {
            throw new \LogicException("This day is not part of this month.");
        }

        return $this->getDay($day->getDayValue() + 1);
    }

    public function getNextMonth($ignoreTimestampLimits = false)
    {
        $year  = $this->yearValue;
        $month = $this->monthValue;

        if ($month == 12) {
            $year += 1;
            $month = 1;
        } else {
            $month += 1;
        }

        return self::getMonth($year, $month, $ignoreTimestampLimits);
    }

    public function getPreviousMonth($ignoreTimestampLimits = false)
    {
        $year  = $this->yearValue;
        $month = $this->monthValue;

        if ($month == 1) {
            $year -= 1;
            $month = 12;
        } else {
            $month -= 1;
        }

        return self::getMonth($year, $month, $ignoreTimestampLimits);
    }

    public function getStartTime()
    {
        return $this->dateTime->getTimestamp();
    }

    public function getEndTime()
    {
        $endDate = clone $this->dateTime;
        $endDate->add(new \DateInterval("P" . count($this->getDays()) . "D"));
        return $endDate->getTimestamp();
    }

    public function contains($day)
    {
        return $day->getMonth()->equals($this);
    }

    public function equals($month)
    {
        return $this->yearValue == $month->yearValue && $this->monthValue == $month->monthValue;
    }
}
