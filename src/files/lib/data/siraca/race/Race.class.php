<?php

namespace wcf\data\siraca\race;
use wcf\data\DatabaseObject;
use wcf\data\ILinkableObject;
use wcf\system\request\IRouteController;
use wcf\system\request\LinkHandler;

class Race extends DatabaseObject implements IRouteController, ILinkableObject {
    protected static $databaseTableName = 'siraca_race';
    
	public function getTitle() {
		return $this->title;
    }
    
	public function getLink() {
		return LinkHandler::getInstance()->getLink('Race', [
			'forceFrontend' => true,
			'object' => $this
		]);
    }
    
	public function __toString() {
		return $this->getTitle();
	}
}