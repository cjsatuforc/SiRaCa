<?php

namespace wcf\data\siraca\participation;

use wcf\system\WCF;

/**
 * Organize positions in titular and waiting list, after a new registration or a participation change.
 */
class ParticipationOrganizer
{

    public static function setParticipation($race, $user, $currentParticipation, $newParticipationType)
    {
        if ($newParticipationType == ParticipationType::ABSENCE) {
            self::removeUser($race, $user, $currentParticipation);
        } else {
            if ($currentParticipation->type == ParticipationType::ABSENCE) {
                self::addUser($race, $user, $newParticipationType);
            }
        }
    }

    private static function removeUser($race, $user, $participation)
    {
        /*
        - titulaire ou attente ?
        - supprimer participation
        - prendre tous les suivants de la même liste et enlever une position
        - si titulaire et qu'elle était pleine et qu'il y a un Présent en liste d'attente, le ramener en titulaire et enlever une position aux suivants
         */
        $action = new ParticipationAction([$participation->getDecoratedObject()], 'delete', []);
        $action->executeAction();

        self::unstackPositions($race, $participation->position + 1, $participation->waitingList);
    }

    /*
    $waitingList: [0,1]
     */
    private static function unstackPositions($race, $fromPosition, $waitingList)
    {
        $list = new ParticipationList();
        $list->getConditionBuilder()->add("siraca_participation.raceID = {$race->raceID}");
        $list->getConditionBuilder()->add("siraca_participation.waitingList = {$waitingList}");
        $list->getConditionBuilder()->add("siraca_participation.position >= {$fromPosition}");
        $list->readObjects();

        $updateList = $list->getObjects();

        $updateData = [];

        foreach ($updateList as $participation) {
            $updateData[$participation->participationID] = $participation->position - 1;
        }

        // TODO Si on peut pas utiliser les actions pour ça, pourquoi les utiliser ailleurs ?

        $statement = WCF::getDB()->prepareStatement(
            "UPDATE wcf" . WCF_N . "_siraca_participation p
            SET position = ?
            WHERE p.participationID = ?");

        WCF::getDB()->beginTransaction();
        foreach ($updateData as $participationID => $position) {
            $statement->execute([
                $position,
                $participationID,
            ]);
        }
        WCF::getDB()->commitTransaction();
    }

    private static function addUser($race, $user, $participationType)
    {
        switch ($participationType) {
            case ParticipationType::PRESENCE_NOT_CONFIRMED:
                self::addUnconfirmedParticipation($race, $user, $participationType);
                break;
            case ParticipationType::PRESENCE:
                self::addPresence($race, $user, $participationType);
                break;
            case ParticipationType::ABSENCE:
                throw new Exception("Illegal state ABSENCE.");
                break;
        }
    }

    private static function addUnconfirmedParticipation($race, $user, $participationType)
    {
        $position = self::countParticipants($race, 1) + 1;

        $action = new ParticipationAction([], 'create', [
            'data' => [
                'raceID'      => $race->raceID,
                'userID'      => $user->userID,
                'type'        => $participationType,
                'waitingList' => 1,
                'position'    => $position,
            ],
        ]);

        $action->executeAction();
    }

    private static function addPresence($race, $user, $participationType)
    {
        $titularCount = self::countParticipants($race, 0);
        $waitingList  = $titularCount == $race->availableSlots ? 1 : 0;
        $position     = self::countParticipants($race, $waitingList) + 1;

        $action = new ParticipationAction([], 'create', [
            'data' => [
                'raceID'      => $race->raceID,
                'userID'      => $user->userID,
                'type'        => $participationType,
                'waitingList' => $waitingList,
                'position'    => $position,
            ],
        ]);

        $action->executeAction();
    }

    private static function countParticipants($race, $waitingList)
    {
        $statement = WCF::getDB()->prepareStatement(
            "SELECT COUNT(*) FROM wcf" . WCF_N . "_siraca_participation p
            WHERE p.raceID = {$race->raceID}
            AND p.waitingList = {$waitingList}");

        $statement->execute();

        return $statement->fetchSingleColumn();
    }

    // private static function getTitularList($race)
    // {
    //     $list = new ParticipationList();
    //     $list->getConditionBuilder()->add("siraca_participation.raceID = {$race->$raceID}");
    //     $list->getConditionBuilder()->add("siraca_participation.waitingList = 0");
    //     $list->readObjects();
    //     return $list->getObjects();
    // }

    // private static function getWaitingList($race)
    // {
    //     $list = new ParticipationList();
    //     $list->getConditionBuilder()->add("siraca_participation.raceID = {$race->$raceID}");
    //     $list->getConditionBuilder()->add("siraca_participation.waitingList = 1");
    //     $list->readObjects();
    //     return $list->getObjects();
    // }
}
