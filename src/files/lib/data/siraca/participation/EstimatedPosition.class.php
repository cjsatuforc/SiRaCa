<?php
namespace wcf\data\siraca\participation;

use wcf\system\WCF;

class EstimatedPosition
{
    public $listType;
    public $position;

    public function __construct($listType, $position)
    {
        $this->listType = $listType;
        $this->position = $position;
    }

    public static function estimateParticipationChangePositions($race, $participation)
    {
        $estimatedPositions = [];

        switch ($participation->type) {
            case ParticipationType::ABSENCE:
                $titularCount             = self::countParticipants($race, ListType::TITULAR);
                $estimatedWaitingPosition = new EstimatedPosition(ListType::WAITING, self::countParticipants($race, ListType::WAITING) + 1);

                if ($titularCount < $race->availableSlots) {
                    $estimatedPositions[ParticipationType::PRESENCE] = new EstimatedPosition(ListType::TITULAR, $titularCount + 1);
                } else {
                    $estimatedPositions[ParticipationType::PRESENCE] = $estimatedWaitingPosition;
                }
                $estimatedPositions[ParticipationType::PRESENCE_NOT_CONFIRMED] = $estimatedWaitingPosition;
                break;

            case ParticipationType::PRESENCE_NOT_CONFIRMED:
                $titularCount = self::countParticipants($race, ListType::TITULAR);
                if ($titularCount < $race->availableSlots) {
                    $estimatedPositions[ParticipationType::PRESENCE] = new EstimatedPosition(ListType::TITULAR, self::findTitularPosition($race, (new \DateTime())->getTimestamp()));
                } else {
                    $estimatedPositions[ParticipationType::PRESENCE] = new EstimatedPosition(ListType::WAITING, $participation->position);
                }
                break;

            case ParticipationType::PRESENCE:
                $titularCount = self::countParticipants($race, ListType::TITULAR);
                if ($participation->listType == ListType::TITULAR) {
                    $estimatedPositions[ParticipationType::PRESENCE_NOT_CONFIRMED] = new EstimatedPosition(ListType::WAITING, self::findWaitingPosition($race, $participation->registrationTime));
                } else {
                    $estimatedPositions[ParticipationType::PRESENCE_NOT_CONFIRMED] = new EstimatedPosition(ListType::WAITING, $participation->position);
                }
                break;
        }
        return $estimatedPositions;
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

    private static function titularListHasFreeSlots($race)
    {
        return countParticipants($race, ListType::TITULAR) < $race->availableSlots;
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
}
