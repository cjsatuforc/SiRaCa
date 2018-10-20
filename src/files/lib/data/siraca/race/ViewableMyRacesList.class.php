<?php
namespace wcf\data\siraca\race;

use wcf\data\siraca\race\MyRacesList;
use wcf\data\siraca\race\ViewableRace;

class ViewableMyRacesList extends MyRacesList
{
    public $decoratorClassName = ViewableRace::class;
}
