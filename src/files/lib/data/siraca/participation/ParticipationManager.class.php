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
            self::removeParticipation($race, $user, $currentParticipation);
        } else {
            switch ($currentParticipation->type) {
                case ParticipationType::ABSENCE:
                    // NEW REGISTRATION
                    self::addParticipation($race, $user, $newParticipationType);
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

    public static function recomputeListsAfterRaceCapacityChange($race, $newCapacity)
    {
        if ($newCapacity == $race->availableSlots) {
            return;
        }

        WCF::getDB()->beginTransaction();

        self::sql("
            UPDATE      wcf" . WCF_N . "_siraca_participation
            SET         listType = 0;
        ");
        self::sql("
            SET         @i:=0;
        ");
        self::sql("
            UPDATE      wcf" . WCF_N . "_siraca_participation
            SET         position = @i:=@i+1,
                        listType = " . ListType::TITULAR . "
            WHERE       type = " . ParticipationType::PRESENCE . "
            ORDER BY    presenceTime ASC
            LIMIT       $newCapacity;
        ");
        self::sql("
            SET         @i:=0;
        ");
        self::sql("
            UPDATE      wcf" . WCF_N . "_siraca_participation
            SET         position = @i:=@i+1,
                        listType = " . ListType::WAITING . "
            WHERE       listType = 0
            ORDER BY    registrationTime ASC;
        ");

        WCF::getDB()->commitTransaction();
    }

    private static function addParticipation($race, $user, $participationType)
    {
        switch ($participationType) {
            case ParticipationType::PRESENCE:
                self::addPresence($race, $user);
                break;
            case ParticipationType::PRESENCE_NOT_CONFIRMED:
                self::addUnconfirmedParticipation($race, $user);
                break;
            case ParticipationType::ABSENCE:
                throw new \LogicException("Illegal state ABSENCE.");
                break;
        }
    }

    private static function addPresence($race, $user)
    {
        $titularCount = self::countParticipants($race, ListType::TITULAR);
        $listType     = $titularCount == $race->availableSlots ? ListType::WAITING : ListType::TITULAR;
        $position     = self::countParticipants($race, $listType) + 1;
        $time         = (new \DateTime())->getTimestamp();

        $action = new ParticipationAction([], 'create', [
            'data' => [
                'raceID'           => $race->raceID,
                'userID'           => $user->userID,
                'type'             => ParticipationType::PRESENCE,
                'listType'         => $listType,
                'position'         => $position,
                'registrationTime' => $time,
                'presenceTime'     => $time,
            ],
        ]);

        $action->executeAction();
    }

    private static function addUnconfirmedParticipation($race, $user)
    {
        $position = self::countParticipants($race, ListType::WAITING) + 1;

        $action = new ParticipationAction([], 'create', [
            'data' => [
                'raceID'           => $race->raceID,
                'userID'           => $user->userID,
                'type'             => ParticipationType::PRESENCE_NOT_CONFIRMED,
                'listType'         => ListType::WAITING,
                'position'         => $position,
                'registrationTime' => (new \DateTime())->getTimestamp(),
            ],
        ]);

        $action->executeAction();
    }

    private static function switchToPresence($race, $user, $participation)
    {
        $time = (new \DateTime())->getTimestamp();

        $action = new ParticipationAction([$participation], 'update', [
            'data' => [
                'type'         => ParticipationType::PRESENCE,
                'presenceTime' => $time,
            ],
        ]);
        $action->executeAction();

        if (self::countParticipants($race, ListType::TITULAR) < $race->availableSlots) {
            $participation->presenceTime = $time; // TODO éviter d'avoir à faire ça
            self::switchToList($race, $participation, ListType::TITULAR);
        }
    }

    private static function switchToUnconfirmed($race, $user, $participation)
    {
        $action = new ParticipationAction([$participation], 'update', [
            'data' => [
                'type'         => ParticipationType::PRESENCE_NOT_CONFIRMED,
                'presenceTime' => null,
            ],
        ]);
        $action->executeAction();

        if ($participation->listType == ListType::TITULAR) {
            self::switchToList($race, $participation, ListType::WAITING);
            self::switchFirstWaitingPresentToTitular($race);
        }
    }

    private static function removeParticipation($race, $user, $participation)
    {
        if ($participation->listType == ListType::TITULAR && self::countParticipants($race, ListType::TITULAR) == $race->availableSlots) {
            // REMOVE FROM FULL TITULAR LIST
            $action = new ParticipationAction([$participation], 'delete', []);
            $action->executeAction();

            self::updateNextPositions($race, $participation->position + 1, ListType::TITULAR, -1);

            self::switchFirstWaitingPresentToTitular($race);
        } else {
            // REMOVE FROM TITULAR LIST WITH FREE SLOTS OR WAITING LIST
            $action = new ParticipationAction([$participation], 'delete', []);
            $action->executeAction();

            self::updateNextPositions($race, $participation->position + 1, $participation->listType, -1);
        }
    }

    private static function switchToList($race, $participation, $toListType, $oldPosition = -1)
    {
        switch ($toListType) {
            case ListType::TITULAR:
                $newPosition = self::findTitularPosition($race, $participation->presenceTime);
                break;
            case ListType::WAITING:
                $newPosition = self::findWaitingPosition($race, $participation->registrationTime);
                break;
        }

        self::updateNextPositions($race, $newPosition, $toListType, +1);

        $action = new ParticipationAction([$participation], 'update', [
            'data' => [
                'listType' => $toListType,
                'position' => $newPosition,
            ],
        ]);
        $action->executeAction();

        self::updateNextPositions($race, $oldPosition != -1 ? $oldPosition + 1 : $participation->position + 1,
            ListType::getOtherType($toListType), -1);
    }

    private static function switchFirstWaitingPresentToTitular($race)
    {
        $statement = WCF::getDB()->prepareStatement(
            "SELECT * FROM wcf" . WCF_N . "_siraca_participation
            WHERE raceID = {$race->raceID}
            AND listType = " . ListType::WAITING . "
            AND type = " . ParticipationType::PRESENCE . "
            ORDER BY position ASC
            LIMIT 1
            "
        );
        $statement->execute();

        $firstWaitingPresent = $statement->fetchObject(Participation::class);
        if ($firstWaitingPresent != null) {
            self::switchToList($race, $firstWaitingPresent, ListType::TITULAR);
        }
    }

    private static function updateNextPositions($race, $fromPosition, $listType, $increment)
    {
        WCF::getDB()->prepareStatement("
            UPDATE  wcf" . WCF_N . "_siraca_participation
            SET     position    =  position + $increment
            WHERE   raceID      =  {$race->raceID}
            AND     listType    =  {$listType}
            AND     position    >= {$fromPosition}
        ")->execute();
    }

    private static function countParticipants($race, $listType)
    {
        $statement = WCF::getDB()->prepareStatement(
            "SELECT COUNT(*) FROM wcf" . WCF_N . "_siraca_participation p
            WHERE p.raceID = {$race->raceID}
            AND p.listType = {$listType}");

        $statement->execute();

        return $statement->fetchSingleColumn();
    }

    private static function findTitularPosition($race, $presenceTime)
    {
        $statement = WCF::getDB()->prepareStatement(
            "SELECT * FROM wcf" . WCF_N . "_siraca_participation p
            WHERE p.raceID = {$race->raceID}
            AND p.listType = " . ListType::TITULAR . "
            AND p.presenceTime > {$presenceTime}
            ORDER BY p.presenceTime ASC LIMIT 1"
        );
        $statement->execute();

        $nextParticipation = $statement->fetchObject(Participation::class);
        if (!$nextParticipation) {
            return self::countParticipants($race, ListType::TITULAR) + 1;
        }
        return $nextParticipation->position;
    }

    private static function findWaitingPosition($race, $registrationTime)
    {
        $statement = WCF::getDB()->prepareStatement(
            "SELECT * FROM wcf" . WCF_N . "_siraca_participation p
            WHERE p.raceID = {$race->raceID}
            AND p.listType = " . ListType::WAITING . "
            AND p.registrationTime > {$registrationTime}
            ORDER BY p.registrationTime ASC LIMIT 1"
        );
        $statement->execute();

        $nextParticipation = $statement->fetchObject(Participation::class);
        if (!$nextParticipation) {
            return self::countParticipants($race, ListType::WAITING) + 1;
        }
        return $nextParticipation->position;
    }

    private static function sql($query)
    {
        WCF::getDB()->prepareStatement($query)->execute();
    }
}
