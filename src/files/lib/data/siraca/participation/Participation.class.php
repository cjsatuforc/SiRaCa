<?php

namespace wcf\data\siraca\participation;

use wcf\data\DatabaseObject;

class Participation extends DatabaseObject
{
    protected static $databaseTableName = 'siraca_participation';

    public function __toString()
    {
        return $this->raceID . " " . $this->userID;
    }
}
