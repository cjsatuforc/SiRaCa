<?php
namespace wcf\data\siraca\race;

use wcf\data\DatabaseObject;
use wcf\data\DatabaseObjectDecorator;
use wcf\data\siraca\participation\ParticipationUtil;
use wcf\data\siraca\race\Race;
use wcf\system\siraca\date\DateUtil;

class ViewableRace extends DatabaseObjectDecorator
{
    protected static $baseClass = Race::class;

    private $participation;

    public function __construct(DatabaseObject $object)
    {
        parent::__construct($object);

        $this->participation = ParticipationUtil::getUserParticipation($this->getDecoratedObject()->raceID);
    }

    public function getParticipation()
    {
        return $this->participation;
    }

    public function getParticipationType()
    {
        return $this->participation->getType();
    }

    public function isParticipant()
    {
        return $this->participation->isRegistered();
    }

    public function isTitular()
    {
        return $this->participation->isTitular();
    }

    public function getParticipationPosition()
    {
        return $this->participation->position;
    }

    public function isParticipationConfirmed()
    {
        return $this->participation->isConfirmed();
    }

    public function getTitularListFreeSlots()
    {
        return $this->availableSlots - $this->titularListCount;
    }

    public function getFormattedStartTime()
    {
        return DateUtil::getFormattedDate($this->getDecoratedObject()->startTime);
    }

    public function __toString()
    {
        return $this->getDecoratedObject()->__toString();
    }
}
