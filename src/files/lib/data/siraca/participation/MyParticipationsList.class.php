<?php

namespace wcf\data\siraca\participation;

use wcf\system\WCF;

class MyParticipationsList extends ParticipationList
{

    public function __construct()
    {
        parent::__construct();

        $userID = WCF::getUser()->userID;
        $this->getConditionBuilder()->add("siraca_participation.userID = $userID");
    }
}
