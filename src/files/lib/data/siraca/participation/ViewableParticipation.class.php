<?php
namespace wcf\data\siraca\participation;

use wcf\data\DatabaseObject;
use wcf\data\DatabaseObjectDecorator;
use wcf\data\user\User;
use wcf\system\WCF;

class ViewableParticipation extends DatabaseObjectDecorator
{
    protected static $baseClass = Participation::class;

    private $user;

    public function __construct(DatabaseObject $decorated)
    {
        parent::__construct($decorated);

        $this->user = new User($decorated->userID);
    }

    public function getType()
    {
        return ParticipationType::getTypes()[$this->type];
    }

    public function isRegistered()
    {
        return $this->type != ParticipationType::ABSENCE;
    }

    public function isConfirmed()
    {
        return $this->type == ParticipationType::PRESENCE;
    }

    public function isTitular()
    {
        return $this->listType == ListType::TITULAR;
    }

    public function getUsername()
    {
        return $this->user->getUsername();
    }

    public function getUserLink()
    {
        return $this->user->getLink();
    }
}
