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
        if (!$month) {
            throw new \LogicException("Month can't be null.");
        }

        $this->month    = $month;
        $this->dayValue = $dayValue;

        $this->dateTime = new \DateTime();
        $this->dateTime->setDate($month->getYearValue(), $month->getMonthValue(), $dayValue);
        try {
            $this->dateTime->setTime(0, 0, 0, 0);
        } catch (\Exception $e) {
            $this->dateTime->setTime(0, 0, 0);
        }
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

    public function getWeekDayValue()
    {
        return $this->dateTime->format("N");
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

    public function isToday()
    {
        return self::getToday()->equals($this);
    }

    public static function getToday()
    {
        $date = new \DateTime('@' . TIME_NOW);
        $date->setTimezone(WCF::getUser()->getTimeZone());
        $year  = $date->format('Y');
        $month = $date->format('n');
        $day   = $date->format('j');

        return new Day(Month::getMonth($year, $month), $day);
    }

    public function equals($day)
    {
        return $this->month->equals($day->month) && $this->dayValue == $day->dayValue;
    }
}
