<?php

namespace wcf\data\siraca\participation;

use wcf\data\DatabaseObject;
use wcf\system\WCF;

class Participation extends DatabaseObject
{
    protected static $databaseTableName = 'siraca_participation';

    public function __toString()
    {
        return $this->raceID . " " . $this->userID;
    }

    public function getType()
    {
        return ParticipationType::getTypes()[$this->type];
    }

    public static function getUserParticipation($raceID)
    {
        $userID = WCF::getUser()->userID;

        $statement = WCF::getDB()->prepareStatement("SELECT * FROM wcf" . WCF_N . "_siraca_participation WHERE userID=$userID AND raceID={$raceID}");
        $statement->execute();

        $participation = $statement->fetchObject(Participation::class);

        if (!$participation) {
            $participation = new Participation(null, ["userID" => $userID, "raceID" => $raceID, "type" => ParticipationType::ABSENCE]);
        }

        return $participation;
    }
}
