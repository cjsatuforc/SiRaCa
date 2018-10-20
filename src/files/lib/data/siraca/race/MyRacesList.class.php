<?php

namespace wcf\data\siraca\race;

use wcf\data\siraca\race\RaceList;
use wcf\system\WCF;

class MyRacesList extends RaceList
{

    public function __construct()
    {
        parent::__construct();

        $userID = WCF::getUser()->userID;

        if (!empty($this->sqlSelects)) {
            $this->sqlSelects .= ',';
        }

        // TODO Pas bien compris pourquoi il faut renseigner les deux. Revoir le code de DatabaseObjectList.
        $this->sqlJoins .= " INNER JOIN wcf" . WCF_N . "_siraca_participation p ON (p.raceID = siraca_race.raceID)";
        $this->sqlConditionJoins .= " INNER JOIN wcf" . WCF_N . "_siraca_participation p ON (p.raceID = siraca_race.raceID)";
    }
}
