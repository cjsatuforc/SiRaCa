<?php

namespace wcf\data\siraca\race;
use wcf\data\DatabaseObject;
use wcf\data\ILinkableObject;
use wcf\system\request\IRouteController;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;

class Race extends DatabaseObject implements IRouteController, ILinkableObject {
    protected static $databaseTableName = 'siraca_race';
    
	public function getTitle() {
		return $this->title;
	}
	
	public function isParticipant() {
		$userID = WCF::getUser()->userID;
		
		// guests cannot participate at all
		// if (!$userID) { // TODO intéressant
		// 	return false;
		// }
		
		$sql = "SELECT	COUNT(*)
			FROM	wcf".WCF_N."_siraca_participation
			WHERE	raceID = ?
				AND userID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute([$this->raceID, $userID]);
		
		return $statement->fetchSingleColumn() > 0;
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