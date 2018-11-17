<?php
namespace wcf\page;

use wcf\data\siraca\race\ViewableRaceMonthList;
use wcf\page\AbstractPage;
use wcf\system\siraca\date\Month;
use wcf\system\WCF;

class RaceCalendarPage extends AbstractPage
{
    private $month;
    private $raceMonth;

    public function readParameters()
    {
        parent::readParameters();
    }

    public function readData()
    {
        parent::readData();

        $date = new \DateTime('@' . TIME_NOW);
        $date->setTimezone(WCF::getUser()->getTimeZone());
        $year  = $date->format('Y');
        $month = $date->format('n');

        $this->month     = new Month($year, $month);
        $this->raceMonth = new ViewableRaceMonthList($this->month);
        $this->raceMonth->readObjects();
    }

    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'month'     => $this->month,
            'raceMonth' => $this->raceMonth,
        ]);
    }
}
