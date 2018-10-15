<?php
namespace wcf\page;
use wcf\page\SortablePage;
use wcf\data\siraca\race\RaceList;

class RaceListPage extends SortablePage {
	
	public $defaultSortField = 'title';
	
	public $objectListClassName = RaceList::class;
	
	public $validSortFields = ['raceID', 'title'];
	
}