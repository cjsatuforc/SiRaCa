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

    public function isUncertain()
    {
        return $this->type == ParticipationType::PRESENCE_NOT_CONFIRMED;
    }

    public function getUsername()
    {
        return $this->user->getUsername();
    }

    public function getUserLink()
    {
        return $this->user->getLink();
    }

    public static function getUserParticipation($raceID)
    {
        $userID = WCF::getUser()->userID;

        $statement = WCF::getDB()->prepareStatement(
            "SELECT * FROM wcf" . WCF_N .
            "_siraca_participation
            WHERE   userID = $userID
            AND     raceID = {$raceID}");

        $statement->execute();
        $participation = $statement->fetchObject(Participation::class);

        if (!$participation) {
            $participation = new Participation(null, [
                "userID" => $userID,
                "raceID" => $raceID,
                "type"   => ParticipationType::ABSENCE]);
        }

        return new ViewableParticipation($participation);
    }
}
