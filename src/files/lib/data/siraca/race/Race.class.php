<?php

namespace wcf\data\siraca\race;

use wcf\data\DatabaseObject;
use wcf\data\ITitledLinkObject;
use wcf\data\siraca\participation\Participation;
use wcf\system\request\IRouteController;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;

class Race extends DatabaseObject implements IRouteController, ITitledLinkObject
{
    protected static $databaseTableName = 'siraca_race';
    private $participation;

    public function isParticipant()
    {
        $userID = WCF::getUser()->userID;

        $sql = "SELECT COUNT(*) FROM wcf" . WCF_N .
            "_siraca_participation
			WHERE   raceID = ?
            AND     userID = ?";

        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$this->raceID, $userID]);

        return $statement->fetchSingleColumn() > 0;
    }

    public function getParticipation()
    {
        // TODO Utiliser Runtime cache au lieu de le faire soi-même, c'est peut-être pas correct ça.
        if (!$this->participation) {
            $this->participation = Participation::getUserParticipation($this->raceID);
        }

        return $this->participation;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getLink()
    {
        return LinkHandler::getInstance()->getLink('Race', [
            'forceFrontend' => true,
            'object'        => $this,
        ]);
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}
