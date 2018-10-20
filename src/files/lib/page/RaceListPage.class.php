<?php
namespace wcf\page;

use wcf\data\siraca\race\ViewableRaceList;
use wcf\page\SortablePage;
use wcf\system\WCF;

class RaceListPage extends SortablePage
{
    // TODO virer Sortable
    public $defaultSortField = 'title';

    public $objectListClassName = ViewableRaceList::class;

    public $validSortFields = ['raceID', 'title'];
}
