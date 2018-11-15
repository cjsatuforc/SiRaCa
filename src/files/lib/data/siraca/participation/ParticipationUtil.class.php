<?php
namespace wcf\data\siraca\participation;

use wcf\system\WCF;

class ParticipationUtil
{
    public static function deleteRaceParticipations(array $raceIDs)
    {
        $participationList = new ParticipationList();
        $participationList->getConditionBuilder()->add('siraca_participation.raceID = ?', $raceIDs);
        $participationList->readObjectIDs();
        $participationIDs = $participationList->getObjectIDs();

        $deleteAction = new ParticipationAction($participationIDs, 'delete');
        $deleteAction->executeAction();
    }

    public static function getUserParticipation($raceID)
    {
        $userID = WCF::getUser()->userID;

        $statement = WCF::getDB()->prepareStatement(
            "SELECT * FROM wcf" . WCF_N .
            "_siraca_participation
            WHERE   userID = $userID
            AND     raceID = $raceID");

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

    public static function getParticipantsSummary($race)
    {
        return "{$race->titularListCount} ({$race->waitingListCount}) / {$race->availableSlots}";
    }
}
