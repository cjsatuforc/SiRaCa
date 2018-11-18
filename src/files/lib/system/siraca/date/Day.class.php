<?php
namespace wcf\system\siraca\date;

use wcf\system\WCF;

class Day
{
    private $dateTime;
    private $dayValue;
    private $dayName;
    private $month;

    public function __construct($month, $dayValue)
    {
        $this->month    = $month;
        $this->dayValue = $dayValue;

        $this->dateTime = new \DateTime();
        $this->dateTime->setDate($month->getYearValue(), $month->getMonthValue(), $dayValue);
        $this->dateTime->setTime(0, 0, 0, 0);
    }

    public function getDayValue()
    {
        return $this->dayValue;
    }

    public function getDayName()
    {
        if (!$this->dayName) {
            $this->dayName = strtolower($this->dateTime->format("l"));
        }
        return $this->dayName;
    }

    public function getMonth()
    {
        return $this->month;
    }

    public function getStartTime()
    {
        return $this->dateTime->getTimestamp();
    }

    public function getEndTime()
    {
        $endDate = clone $this->dateTime;
        $endDate->add(new \DateInterval("P1D"));
        return $endDate->getTimestamp();
    }

    public static function getToday()
    {
        $date = new \DateTime('@' . TIME_NOW);
        $date->setTimezone(WCF::getUser()->getTimeZone());
        $year  = $date->format('Y');
        $month = $date->format('n');
        $day   = $date->format('j');

        return new Day(new Month($year, $month), $day);
    }
}
