<?php
namespace wcf\system\siraca\date;

class Month
{
    private $yearValue  = 0;
    private $monthValue = 0;

    private $days;
    private $dateTime;

    public function __construct($year, $month)
    {
        $this->yearValue  = $year;
        $this->monthValue = $month;

        $this->dateTime = new \DateTime();
        $this->dateTime->setDate($this->yearValue, $this->monthValue, 1);
    }

    public function getYearValue()
    {
        return $this->yearValue;
    }

    public function getMonthValue()
    {
        return $this->monthValue;
    }

    public function getDays()
    {
        if ($this->days == null) {
            $dayCount = $this->dateTime->format('t');

            $this->days = [];
            for ($i = 1; $i <= $dayCount; $i++) {
                $this->days[] = new Day($this, $i);
            }
        }

        return $this->days;
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

        return new Month($year, $month, 1);
    }

    public function getStartTime()
    {
        return $this->dateTime->getTimestamp();
    }

    public function getEndTime()
    {
        $endDate = clone $this->dateTime;
        $endDate->setDate($this->yearValue, $this->getNextMonth()->monthValue, 1);
        return $endDate->getTimestamp();
    }
}
