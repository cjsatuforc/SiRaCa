<?php

namespace wcf\data\siraca\participation;

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
        $time = (new \DateTime())->getTimestamp();

        $action = new ParticipationAction([], 'create', [
            'data' => [
                'raceID'           => $race->raceID,
                'userID'           => $user->userID,
                'type'             => ParticipationType::PRESENCE,
                'registrationTime' => $time,
                'presenceTime'     => $time,
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
                'registrationTime' => (new \DateTime())->getTimestamp(),
            ],
        ]);

        $action->executeAction();
    }

    private static function switchToPresence($race, $user, $participation)
    {
        $action = new ParticipationAction([$participation], 'update', [
            'data' => [
                'type'         => ParticipationType::PRESENCE,
                'presenceTime' => (new \DateTime())->getTimestamp(),
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
