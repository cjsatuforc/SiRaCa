<?php

namespace wcf\data\siraca\race;

use wcf\data\DatabaseObject;
use wcf\data\ITitledLinkObject;
use wcf\system\request\IRouteController;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;

class Race extends DatabaseObject implements IRouteController, ITitledLinkObject
{
    protected static $databaseTableName = 'siraca_race';
    private $participation;

    public function getTitle()
    {
        return $this->title;
    }

    public function getLink()
    {
        return LinkHandler::getInstance()->getLink('Race', [
            'forceFrontend' => true,
            'object'        => $this,
        ]);
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}
