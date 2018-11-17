<?php
namespace wcf\data\siraca\race;

use wcf\data\siraca\race\RaceList;
use wcf\data\siraca\race\ViewableRace;

class ViewableRaceMonthList extends RaceList
{
    public $decoratorClassName = ViewableRace::class;
    private $month;
    private $racesByDayValue;

    public function __construct($month)
    {
        parent::__construct();

        $this->month = $month;
        $this->getConditionBuilder()->add("startTime >= ? AND startTime < ?", [$month->getStartTime(), $month->getEndTime()]);
    }

    public function readObjects()
    {
        parent::readObjects();

        $this->racesByDayValue = [];

        foreach ($this->objects as $race) {
            $raceDate = new \DateTime();
            $raceDate->setTimestamp($race->startTime);
            $raceDayValue = $raceDate->format("d");

            if (!array_key_exists($raceDayValue, $this->racesByDayValue)) {
                $this->racesByDayValue[$raceDayValue] = [];
            }
            $this->racesByDayValue[$raceDayValue][] = $race;
        }
    }

    public function getDayRaces($dayValue)
    {
        if (!array_key_exists($dayValue, $this->racesByDayValue)) {
            return [];
        }

        return $this->racesByDayValue[$dayValue];
    }
}
