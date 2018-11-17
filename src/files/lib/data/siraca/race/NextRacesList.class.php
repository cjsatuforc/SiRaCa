<?php
namespace wcf\data\siraca\race;

use wcf\data\DatabaseObjectList;
use wcf\data\siraca\race\Race;

class NextRacesList extends RaceList
{
    public $className = Race::class;

    public function __construct()
    {
        parent::__construct();

        // TODO Time Zone ?
        $currentTimestamp = (new \DateTime())->getTimestamp();

        $this->getConditionBuilder()->add("siraca_race.startTime >= $currentTimestamp");
        $this->sqlOrderBy = "siraca_race.startTime";
    }
}
