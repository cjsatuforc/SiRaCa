<?php
namespace wcf\page;

use wcf\data\siraca\race\ViewableDailyRacesList;
use wcf\page\AbstractPage;
use wcf\system\exception\IllegalLinkException;
use wcf\system\siraca\date\Day;
use wcf\system\siraca\date\Month;
use wcf\system\WCF;

class RaceDayPage extends AbstractPage
{
    private $races;

    public function readParameters()
    {
        parent::readParameters();

        $yearValue = $monthValue = $dayValue = 0;

        if (isset($_REQUEST['year'])) {
            $yearValue = intval($_REQUEST['year']);
        }
        if (isset($_REQUEST['month'])) {
            $monthValue = intval($_REQUEST['month']);
        }
        if (isset($_REQUEST['day'])) {
            $dayValue = intval($_REQUEST['day']);
        }

        if (!$yearValue || !$monthValue || !$dayValue) {
            throw new IllegalLinkException();
        }

        $month = Month::getMonth($yearValue, $monthValue);

        if ($month == null) {
            throw new IllegalLinkException();
        }

        $this->day = $month->getDay($dayValue);

        if ($this->day == null) {
            throw new IllegalLinkException();
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
