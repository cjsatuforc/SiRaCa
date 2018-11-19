<?php

namespace wcf\data\siraca\participation;

use wcf\system\siraca\date\DateUtil;
use wcf\system\WCF;

/**
 * Organize positions in titular and waiting list.
 */
class ParticipationManager
{
    public static function setParticipation($race, $user, $currentParticipation, $newParticipationType)
    {
        if ($currentParticipation->type == $newParticipationType) {
            return;
        }

        if ($newParticipationType == ParticipationType::ABSENCE) {
            self::removeParticipation($race, $user, $currentParticipation);
        } else {
            switch ($currentParticipation->type) {
                case ParticipationType::ABSENCE:
                    self::addParticipation($race, $user, $newParticipationType);
                    break;
                case ParticipationType::PRESENCE_NOT_CONFIRMED:
                    self::switchToPresence($race, $user, $currentParticipation);
                    break;
                case ParticipationType::PRESENCE:
                    self::switchToUnconfirmed($race, $user, $currentParticipation);
                    break;
                default:
                    throw new \LogicException("Illegal type.");
            }
        }

        self::computePositions($race, $race->availableSlots);
    }

    public static function computePositions($race, $newCapacity)
    {
        WCF::getDB()->beginTransaction();
        // Reset participations
        self::sql("
            UPDATE      wcf" . WCF_N . "_siraca_participation
            SET         listType    = -1
            WHERE       raceID      = {$race->raceID};
        ");

        self::sql("
            SET         @i:=0;
        ");
        // Create titular list
        self::sql("
            UPDATE      wcf" . WCF_N . "_siraca_participation
            SET         position    = @i:=@i+1,
                        listType    = " . ListType::TITULAR . "
            WHERE       raceID      = {$race->raceID}
            AND         type        = " . ParticipationType::PRESENCE . "
            ORDER BY    presenceTime ASC,
                        registrationTime ASC
            LIMIT       $newCapacity;
        ");

        self::sql("
            SET         @i:=0;
        ");
        // Create waiting list
        self::sql("
            UPDATE      wcf" . WCF_N . "_siraca_participation
            SET         position    = @i:=@i+1,
                        listType    = " . ListType::WAITING . "
            WHERE       raceID      = {$race->raceID}
            AND         listType    = -1
            ORDER BY    registrationTime ASC,
                        presenceTime ASC;
        ");
        // Store summary on race
        self::sql("
            UPDATE      wcf" . WCF_N . "_siraca_race
            SET         participationCount  = ( SELECT COUNT(*) FROM wcf" . WCF_N . "_siraca_participation
                                                    WHERE   raceID   = {$race->raceID} ),
                        titularListCount    = ( SELECT COUNT(*) FROM wcf" . WCF_N . "_siraca_participation
                                                    WHERE   raceID   = {$race->raceID}
                                                    AND     listType = " . ListType::TITULAR . "),
                        waitingListCount    = ( SELECT COUNT(*) FROM wcf" . WCF_N . "_siraca_participation
                                                    WHERE   raceID   = {$race->raceID}
                                                    AND     listType = " . ListType::WAITING . ")
            WHERE       raceID = {$race->raceID};
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
        $time = DateUtil::getTimestamp();

        $action = new ParticipationAction([], 'create', [
            'data' => [
                'raceID'           => $race->raceID,
                'userID'           => $user->userID,
                'type'             => ParticipationType::PRESENCE,
                'registrationTime' => $time,
                'presenceTime'     => $time,
                'listType'         => -1,
                'position'         => -1,
            ],
        ]);

        $action->executeAction();
    }

    private static function addUnconfirmedParticipation($race, $user)
    {
        $action = new ParticipationAction([], 'create', [
            'data' => [
                'raceID'           => $race->raceID,
                'userID'           => $user->userID,
                'type'             => ParticipationType::PRESENCE_NOT_CONFIRMED,
                'registrationTime' => DateUtil::getTimestamp(),
                'listType'         => -1,
                'position'         => -1,
            ],
        ]);

        $action->executeAction();
    }

    private static function switchToPresence($race, $user, $participation)
    {
        $action = new ParticipationAction([$participation], 'update', [
            'data' => [
                'type'         => ParticipationType::PRESENCE,
                'presenceTime' => DateUtil::getTimestamp(),
            ],
        ]);
        $action->executeAction();
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
    }

    private static function removeParticipation($race, $user, $participation)
    {
        $action = new ParticipationAction([$participation], 'delete', []);
        $action->executeAction();
    }

    private static function sql($query)
    {
        WCF::getDB()->prepareStatement($query)->execute();
    }
}
