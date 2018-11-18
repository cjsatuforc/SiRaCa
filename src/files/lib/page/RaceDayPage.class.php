<?php
namespace wcf\page;

use wcf\data\siraca\race\ViewableDailyRacesList;
use wcf\page\AbstractPage;
use wcf\system\siraca\date\Day;
use wcf\system\siraca\date\Month;
use wcf\system\WCF;

class RaceDayPage extends AbstractPage
{
    private $races;

    public function readParameters()
    {
        parent::readParameters();

        $year;
        $month;
        $day;

        if (isset($_REQUEST['year'])) {
            $year = intval($_REQUEST['year']);
        }
        if (isset($_REQUEST['month'])) {
            $month = intval($_REQUEST['month']);
        }
        if (isset($_REQUEST['day'])) {
            $day = intval($_REQUEST['day']);
        }

        if (!$year | !$month | !$day) {
            $this->day = Day::getToday();
        } else {
            $this->day = new Day(new Month($year, $month), $day);
        }
    }

    public function readData()
    {
        parent::readData();

        $list = new ViewableDailyRacesList($this->day);
        $list->readObjects();
        $this->races = $list->getObjects();
    }

    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'day'   => $this->day,
            'races' => $this->races,
        ]);
    }
}
