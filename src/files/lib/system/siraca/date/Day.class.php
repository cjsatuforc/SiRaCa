<?php
namespace wcf\system\siraca\date;

class Day
{
    private $dateTime;
    private $dayValue;
    private $month;

    public function __construct($month, $dayValue)
    {
        $this->month    = $month;
        $this->dayValue = $dayValue;

        $this->dateTime = new \DateTime();
        $this->dateTime->setDate($month->getYearValue(), $month->getMonthValue(), $dayValue);
    }

    public function getDayValue()
    {
        return $this->dayValue;
    }

    public function getStartTime()
    {
        return $this->dateTime->getTimestamp();
    }

    public function getEndTime()
    {
        $endDate = clone $this->dateTime;
        $endDate->add(new DateInterval("P1d"));
        return $endDate->getTimestamp();
    }
}
