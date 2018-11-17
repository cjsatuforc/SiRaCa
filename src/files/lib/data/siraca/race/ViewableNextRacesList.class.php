<?php
namespace wcf\data\siraca\race;

use wcf\data\siraca\race\RaceList;
use wcf\data\siraca\race\ViewableRace;

class ViewableNextRacesList extends NextRacesList
{
    public $decoratorClassName = ViewableRace::class;
}
