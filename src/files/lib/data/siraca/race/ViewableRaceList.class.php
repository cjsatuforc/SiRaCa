<?php
namespace wcf\data\siraca\race;

use wcf\data\siraca\race\RaceList;
use wcf\data\siraca\race\ViewableRace;

class ViewableRaceList extends RaceList
{
    public $decoratorClassName = ViewableRace::class;
}
