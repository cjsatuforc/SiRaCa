<?php
namespace wcf\page;

use wcf\data\siraca\race\ViewableNextRacesList;
use wcf\system\WCF;

class RaceListPage extends MultipleLinkPage
{
    // public $defaultSortField = 'title';
    // public $validSortFields = ['raceID', 'title'];

    public $objectListClassName = ViewableNextRacesList::class;
}
