<?php
namespace wcf\system\siraca\view;

class MonthView
{
    private $month;
    private $days;

    public function __construct($month)
    {
        $this->month = $month;
    }

    public function getDays()
    {
        if (!$this->days) {
            $this->days = [];

            $firstDayWeekValue = $this->month->getFirstDay()->getWeekDayValue();
            if ($firstDayWeekValue > 1) {
                $prevMonth = $this->month->getPreviousMonth(true);
                // Previous month monday
                $prevMonthDay    = $prevMonth->getDayFromEnd(2 - $firstDayWeekValue);
                $prevMondayValue = $prevMonthDay->getDayValue();

                do {
                    $this->days[] = $prevMonthDay;
                } while ($prevMonthDay = $prevMonth->getNextDay($prevMonthDay));
            }

            $this->days = array_merge($this->days, $this->month->getDays());

            $lastDayWeekValue = $this->month->getLastDay()->getWeekDayValue();
            if ($lastDayWeekValue < 7) {
                $nextMonth    = $this->month->getNextMonth(true);
                $nextDay      = $nextMonth->getFirstDay();
                $this->days[] = $nextDay;

                do {
                    $nextDay      = $nextMonth->getNextDay($nextDay);
                    $this->days[] = $nextDay;
                } while ($nextDay->getWeekDayValue() != 7);
            }
        }

        return $this->days;
    }
}
