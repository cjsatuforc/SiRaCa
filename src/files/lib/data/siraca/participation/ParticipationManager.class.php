<?php

namespace wcf\data\siraca\participation;

use wcf\system\WCF;

/**
 * Organize positions in titular and waiting list, after a new registration or a participation change.
 */
class ParticipationManager
{
    // TODO enlever les params inutilisés

    public static function setParticipation($race, $user, $currentParticipation, $newParticipationType)
    {
        if ($currentParticipation->type == $newParticipationType) {
            return;
        }

        if ($newParticipationType == ParticipationType::ABSENCE) {
            // REMOVE REGISTRATION
            self::removeUser($race, $user, $currentParticipation);
        } else {
            switch ($currentParticipation->type) {
                case ParticipationType::ABSENCE:
                    // NEW REGISTRATION
                    self::addUser($race, $user, $newParticipationType);
                    break;
                case ParticipationType::PRESENCE_NOT_CONFIRMED:
                    self::switchToPresence($race, $user, $currentParticipation);
                    break;
                case ParticipationType::PRESENCE:
                    self::switchToUnconfirmed($race, $user, $currentParticipation);
                    break;
            }
        }
    }

    private static function switchToUnconfirmed($race, $user, $currentParticipation)
    {
        if ($currentParticipation->waitingList == 1) {
            $action = new ParticipationAction([$currentParticipation], 'update', [
                'data' => [
                    'type'         => ParticipationType::PRESENCE_NOT_CONFIRMED,
                    'presenceTime' => null,
                ],
            ]);
            $action->executeAction();
        }
    }

    private static function switchToPresence($race, $user, $currentParticipation)
    {
        $titularCount = self::countParticipants($race, 0);

        if ($titularCount == $race->availableSlots) {
            // STAY IN WAITING LIST AT SAME POSITION
            $action = new ParticipationAction([$currentParticipation], 'update', [
                'data' => [
                    'type'         => ParticipationType::PRESENCE,
                    'presenceTime' => (new \DateTime())->getTimestamp(),
                ],
            ]);
            $action->executeAction();
        } else {
            // MOVE TO END OF TITULAR LIST
            $action = new ParticipationAction([$currentParticipation], 'update', [
                'data' => [
                    'type'         => ParticipationType::PRESENCE,
                    'waitingList'  => 0,
                    'position'     => $titularCount + 1,
                    'presenceTime' => (new \DateTime())->getTimestamp(),
                ],
            ]);
            $action->executeAction();
        }
    }

    private static function removeUser($race, $user, $participation)
    {
        // TODO peut-être plutôt récupérer les 2 listes complètes, faire les traitements en local et update la bdd à la fin.

        if ($participation->waitingList == 0 && self::countParticipants($race, 0) == $race->availableSlots) {
            // REMOVE FROM FULL TITULAR LIST
            $action = new ParticipationAction([$participation], 'delete', []);
            $action->executeAction();

            self::updateNextPositions($race, $participation->position + 1, 0, -1);

            $firstWaitingPresent = self::findFirstPresenceInWaitingList($race);
            if ($firstWaitingPresent != null) {
                self::updateNextPositions($race, $firstWaitingPresent->position + 1, 1, -1);

                $newTitularPosition = self::findTitularPosition($race, $firstWaitingPresent->presenceTime);
                self::updateNextPositions($race, $newTitularPosition, 0, +1);

                $action = new ParticipationAction([$firstWaitingPresent], 'update', [
                    'data' => [
                        'waitingList' => 0,
                        'position'    => $newTitularPosition,
                    ],
                ]);

                $action->executeAction();
            }

        } else {
            // REMOVE FROM TITULAR LIST WITH FREE SLOTS OR WAITING LIST
            $action = new ParticipationAction([$participation], 'delete', []);
            $action->executeAction();

            self::updateNextPositions($race, $participation->position + 1, $participation->waitingList, -1);
        }
    }

    /*
    waitingList: [0,1]
     */
    private static function updateNextPositions($race, $fromPosition, $waitingList, $increment)
    {
        $list = new ParticipationList();
        $list->getConditionBuilder()->add("siraca_participation.raceID = {$race->raceID}");
        $list->getConditionBuilder()->add("siraca_participation.waitingList = {$waitingList}");
        $list->getConditionBuilder()->add("siraca_participation.position >= {$fromPosition}");
        $list->readObjects();

        $updateList = $list->getObjects();

        if (empty($updateList)) {
            return;
        }

        $updateData = [];

        foreach ($updateList as $participation) {
            $updateData[$participation->participationID] = $participation->position + $increment;
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
                self::addUnconfirmedParticipation($race, $user);
                break;
            case ParticipationType::PRESENCE:
                self::addPresence($race, $user);
                break;
            case ParticipationType::ABSENCE:
                throw new Exception("Illegal state ABSENCE.");
                break;
        }
    }

    private static function addUnconfirmedParticipation($race, $user)
    {
        $position = self::countParticipants($race, 1) + 1;

        $action = new ParticipationAction([], 'create', [
            'data' => [
                'raceID'           => $race->raceID,
                'userID'           => $user->userID,
                'type'             => ParticipationType::PRESENCE_NOT_CONFIRMED,
                'waitingList'      => 1,
                'position'         => $position,
                'registrationTime' => (new \DateTime())->getTimestamp(),
            ],
        ]);

        $action->executeAction();
    }

    private static function addPresence($race, $user)
    {
        $titularCount = self::countParticipants($race, 0);
        $waitingList  = $titularCount == $race->availableSlots ? 1 : 0;
        $position     = self::countParticipants($race, $waitingList) + 1;
        $time         = (new \DateTime())->getTimestamp();

        $action = new ParticipationAction([], 'create', [
            'data' => [
                'raceID'           => $race->raceID,
                'userID'           => $user->userID,
                'type'             => ParticipationType::PRESENCE,
                'waitingList'      => $waitingList,
                'position'         => $position,
                'registrationTime' => $time,
                'presenceTime'     => $time,
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

    private static function findFirstPresenceInWaitingList($race)
    {
        // TODO pas trouvé comment faire ça en une seule requête, ça marche dans phpmyadmin mais pas easyPHP
        $statement = WCF::getDB()->prepareStatement(
            "SELECT MIN(p.position) FROM wcf" . WCF_N . "_siraca_participation p
            WHERE p.raceID = {$race->raceID}
            AND p.waitingList = 1
            AND p.type = " . ParticipationType::PRESENCE . "
            LIMIT 1
            "
        );
        $statement->execute();
        $minPosition = $statement->fetchSingleColumn();

        if (!$minPosition) {
            return null;
        }

        $statement = WCF::getDB()->prepareStatement(
            "SELECT * FROM wcf" . WCF_N . "_siraca_participation p
            WHERE p.raceID = {$race->raceID}
            AND p.waitingList = 1
            AND p.type = " . ParticipationType::PRESENCE . "
            AND p.position = $minPosition
            LIMIT 1
            "
        );

        $statement->execute();

        $participation = $statement->fetchObject(Participation::class);

        return $participation ? $participation : null;
    }

    private static function findTitularPosition($race, $presenceTime)
    {
        $statement = WCF::getDB()->prepareStatement(
            "SELECT * FROM wcf" . WCF_N . "_siraca_participation p
            WHERE p.raceID = {$race->raceID}
            AND p.waitingList = 0
            AND p.presenceTime > {$presenceTime}
            ORDER BY p.presenceTime ASC LIMIT 1"
        );
        $statement->execute();

        $nextParticipation = $statement->fetchObject(Participation::class);
        if (!$nextParticipation) {
            return self::countParticipants($race, 0) + 1;
        }
        return $nextParticipation->position;
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
