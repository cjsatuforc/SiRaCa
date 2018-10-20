<?php

namespace wcf\page;

use wcf\data\siraca\race\ViewableMyRacesList;
use wcf\system\WCF;

class MyRacesListPage extends MultipleLinkPage
{
    public $objectListClassName = ViewableMyRacesList::class;
}
