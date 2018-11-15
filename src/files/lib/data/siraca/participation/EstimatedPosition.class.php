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
                if ($participation->listType == ListType::TITULAR) {
                    $estimatedPositions[ParticipationType::PRESENCE_NOT_CONFIRMED] = new EstimatedPosition(ListType::WAITING, self::findWaitingPosition($race, $participation));
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

    private static function findWaitingPosition($race, $participation)
    {
        $statement = WCF::getDB()->prepareStatement(
            "SELECT * FROM wcf" . WCF_N . "_siraca_participation p
            WHERE p.raceID = {$race->raceID}
            AND p.listType = " . ListType::WAITING . "
            AND p.registrationTime > {$participation->registrationTime}
            ORDER BY p.registrationTime ASC,
            p.presenceTime ASC
            LIMIT 1"
        );
        $statement->execute();

        $nextParticipation = $statement->fetchObject(Participation::class);

        $firstPresentDelta   = 0;
        $firstWaitingPresent = self::findFirstWaitingPresent($race);

        if ($firstWaitingPresent != null && $firstWaitingPresent->registrationTime < $participation->registrationTime) {
            $firstPresentDelta++;
        }

        if (!$nextParticipation) {
            return self::countParticipants($race, ListType::WAITING) + 1 - $firstPresentDelta;
        }
        return $nextParticipation->position - $firstPresentDelta;
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

    private static function findFirstWaitingPresent($race)
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

        return $statement->fetchObject(Participation::class);
    }
}
