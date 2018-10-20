<?php

namespace wcf\page;

use wcf\data\siraca\participation\ViewableMyParticipationList;

class MyParticipationsListPage extends MultipleLinkPage
{
    public $objectListClassName = ViewableMyParticipationList::class;
}
