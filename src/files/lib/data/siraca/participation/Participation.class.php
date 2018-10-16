<?php

namespace wcf\data\siraca\participation;
use wcf\data\DatabaseObject;
use wcf\system\WCF;

class Participation extends DatabaseObject {
    protected static $databaseTableName = 'siraca_participation';
    
    // private $raceData;

	// protected function handleData($data) {
	// 	parent::handleData($data);
		
	// 	$this->raceData = @unserialize($this->race);
	// 	if (!is_array($this->raceData)) $this->raceData = [];
    // }
    
	public function __toString() {
		return $this->raceID." ".$this->userID;
	}
	
	// public static function getParticipation($raceID, $userID) {
	// 	$sql = "SELECT	*
	// 		FROM	wcf".WCF_N."_siraca_participation
	// 		WHERE	raceID = ?
	// 			AND userID = ?";
	// 	$statement = WCF::getDB()->prepareStatement($sql);
	// 	$statement->execute([
	// 		$raceID,
	// 		$userID
	// 	]);
		
	// 	return $statement->fetchObject(self::class);
	// }
}