<?php
namespace wcf\data\siraca\race;

use wcf\data\siraca\race\RaceList;
use wcf\data\siraca\race\ViewableRace;
use wcf\system\WCF;
use wcf\system\siraca\date\DateUtil;

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
            $raceDate = DateUtil::getNewDate($race->startTime);
            $raceDayValue = $raceDate->format("j");

            if (!array_key_exists($raceDayValue, $this->racesByDayValue)) {
                $this->racesByDayValue[$raceDayValue] = [];
            }
            $this->racesByDayValue[$raceDayValue][] = $race;
        }
    }

    public function getDayRaces($dayValue, $maxLength = -1)
    {
        if (!array_key_exists($dayValue, $this->racesByDayValue)) {
            return [];
        }

        if ($maxLength > 0 && count($this->racesByDayValue[$dayValue]) > $maxLength) {
            return array_slice($this->racesByDayValue[$dayValue], 0, $maxLength);
        }
        return $this->racesByDayValue[$dayValue];
    }
}
