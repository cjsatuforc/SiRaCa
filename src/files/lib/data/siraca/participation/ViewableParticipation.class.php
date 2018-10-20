<?php
namespace wcf\data\siraca\participation;

use wcf\data\DatabaseObject;
use wcf\data\DatabaseObjectDecorator;
use wcf\data\siraca\race\Race;

class ViewableParticipation extends DatabaseObjectDecorator
{
    protected static $baseClass = Participation::class;

    private $race;

    public function __construct(DatabaseObject $object)
    {
        parent::__construct($object);

        $this->race = new Race($this->getDecoratedObject()->raceID);
    }

    public function getRace()
    {
        return $this->race;
    }
}
