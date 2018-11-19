<?php
namespace wcf\system\siraca\date;

use wcf\system\WCF;

class Month
{
    private $yearValue  = 0;
    private $monthValue = 0;
    private $monthName;

    private $days;
    private $dateTime;

    private function __construct($year, $month)
    {
        $this->yearValue  = $year;
        $this->monthValue = $month;

        $this->dateTime = new \DateTime();
        $this->dateTime->setDate($this->yearValue, $this->monthValue, 1);
    }

    public static function getMonth($yearValue, $monthValue)
    {
        if ($yearValue < DateUtil::MIN_YEAR || $yearValue > DateUtil::MAX_YEAR
            || $monthValue < 1 || $monthValue > 12) {
            return null;
        }

        return new Month($yearValue, $monthValue);
    }

    public static function getCurrentMonth()
    {
        $date = new \DateTime('@' . TIME_NOW);
        $date->setTimezone(WCF::getUser()->getTimeZone());
        $year  = $date->format('Y');
        $month = $date->format('n');

        return new Month($year, $month);
    }

    public function isCurrentMonth()
    {
        $date = new \DateTime('@' . TIME_NOW);
        $date->setTimezone(WCF::getUser()->getTimeZone());
        $yearValue  = $date->format('Y');
        $monthValue = $date->format('n');
        return $this->monthValue == $monthValue && $this->yearValue == $yearValue;
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

    public function getLastDay()
    {
        $dayCount = $this->dateTime->format('t');
        return $this->getDays()[$dayCount];
    }

    public function getDay($dayValue)
    {
        if ($dayValue < 1 || $dayValue > $this->getLastDay()->getDayValue()) {
            return null;
        }

        return $this->days[$dayValue];
    }

    public function getNextMonth()
    {
        $year  = $this->yearValue;
        $month = $this->monthValue;

        if ($month == 12) {
            $year += 1;
            $month = 1;
        } else {
            $month += 1;
        }

        return self::getMonth($year, $month);
    }

    public function getPreviousMonth()
    {
        $year  = $this->yearValue;
        $month = $this->monthValue;

        if ($month == 1) {
            $year -= 1;
            $month = 12;
        } else {
            $month -= 1;
        }

        return self::getMonth($year, $month);
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
}
