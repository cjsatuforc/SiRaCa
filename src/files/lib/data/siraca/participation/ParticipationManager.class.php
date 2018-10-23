<?php
namespace wcf\data\siraca\participation;

class ParticipationManager
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
}
