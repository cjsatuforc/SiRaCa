<?php
namespace wcf\data\siraca\race;

use wcf\data\DatabaseObject;
use wcf\data\DatabaseObjectDecorator;
use wcf\data\siraca\participation\Participation;
use wcf\data\siraca\participation\ParticipationType;
use wcf\data\siraca\race\Race;

class ViewableRace extends DatabaseObjectDecorator
{
    protected static $baseClass = Race::class;

    private $participation;

    public function __construct(DatabaseObject $object)
    {
        parent::__construct($object);

        $this->participation = Participation::getUserParticipation($this->getDecoratedObject()->raceID);
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
        return $this->getParticipationType()->type != ParticipationType::ABSENCE;
    }

    public function __toString()
    {
        return $this->getDecoratedObject()->__toString();
    }
}
