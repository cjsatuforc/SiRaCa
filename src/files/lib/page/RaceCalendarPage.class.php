<?php
namespace wcf\page;

use wcf\data\siraca\race\ViewableRaceMonthList;
use wcf\page\AbstractPage;
use wcf\system\exception\IllegalLinkException;
use wcf\system\siraca\date\DateUtil;
use wcf\system\siraca\date\Month;
use wcf\system\siraca\view\MonthView;
use wcf\system\WCF;

class RaceCalendarPage extends AbstractPage
{
    private $month;
    private $raceMonth;
    private $monthView;

    public function readParameters()
    {
        parent::readParameters();

        $yearValue = $monthValue = 0;

        if (isset($_REQUEST['year'])) {
            $yearValue = intval($_REQUEST['year']);
        }
        if (isset($_REQUEST['month'])) {
            $monthValue = intval($_REQUEST['month']);
        }

        if (!$yearValue && !$monthValue) {
            $this->month = Month::getCurrentMonth();
        } else {
            if (!$yearValue) {
                $yearValue = DateUtil::getCurrentYear();
            }
            if (!$monthValue) {
                $monthValue = DateUtil::getCurrentMonth();
            }
            $this->month = Month::getMonth($yearValue, $monthValue);
        }

        if ($this->month == null) {
            throw new IllegalLinkException();
        }
    }

    public function readData()
    {
        parent::readData();

        $this->raceMonth = new ViewableRaceMonthList($this->month);
        $this->raceMonth->readObjects();

        $this->monthView = new MonthView($this->month);
    }

    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'month'     => $this->month,
            'monthView' => $this->monthView,
            'raceMonth' => $this->raceMonth,
        ]);
    }
}
