<?php
namespace wcf\page;

use wcf\data\siraca\race\ViewableRaceList;
use wcf\page\SortablePage;

class RaceListPage extends SortablePage
{
    // TODO virer Sotable
    public $defaultSortField = 'title';

    public $objectListClassName = ViewableRaceList::class;

    public $validSortFields = ['raceID', 'title'];

}
