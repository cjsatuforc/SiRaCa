<?php

namespace wcf\data\siraca\race;

use wcf\data\DatabaseObject;
use wcf\data\ITitledLinkObject;
use wcf\data\siraca\participation\Participation;
use wcf\data\siraca\participation\ParticipationType;
use wcf\system\request\IRouteController;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;

class Race extends DatabaseObject implements IRouteController, ITitledLinkObject
{
    protected static $databaseTableName = 'siraca_race';
    private $participation;

    public function isParticipant()
    {
        return $this->getParticipationType()->type != ParticipationType::ABSENCE;
    }

    public function getParticipation()
    {
        if (!$this->participation) {
            $this->participation = Participation::getUserParticipation($this->raceID);
        }

        return $this->participation;
    }

    public function getParticipationType()
    {
        return $this->getParticipation()->getType();
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
