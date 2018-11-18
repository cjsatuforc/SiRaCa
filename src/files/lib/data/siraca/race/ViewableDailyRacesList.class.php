<?php

namespace wcf\data\siraca\race;

use wcf\data\siraca\race\RaceList;
use wcf\system\WCF;

class ViewableDailyRacesList extends RaceList
{
    public $decoratorClassName = ViewableRace::class;

    public function __construct($day)
    {
        parent::__construct();

        $this->getConditionBuilder()->add("startTime >= ? AND startTime < ?", [$day->getStartTime(), $day->getEndTime()]);
    }
}
