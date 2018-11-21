<?php
namespace wcf\data\siraca\race;

use wcf\data\siraca\race\Race;
use wcf\system\siraca\date\DateUtil;

class NextRacesList extends RaceList
{
    public $className = Race::class;

    public function __construct()
    {
        parent::__construct();

        // TODO Time Zone ?
        $currentTimestamp = DateUtil::getTimestamp();

        $this->getConditionBuilder()->add("startTime >= $currentTimestamp");
        $this->sqlOrderBy = "startTime ASC, title ASC";
    }
}
