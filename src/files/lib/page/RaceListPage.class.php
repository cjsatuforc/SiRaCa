<?php
namespace wcf\page;

use wcf\data\siraca\race\RaceList;
use wcf\page\SortablePage;

class RaceListPage extends SortablePage
{
    // TODO virer Sotable
    public $defaultSortField = 'title';

    public $objectListClassName = RaceList::class;

    public $validSortFields = ['raceID', 'title'];

}
