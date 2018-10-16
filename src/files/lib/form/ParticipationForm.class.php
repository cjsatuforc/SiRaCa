<?php
namespace wcf\form;
use wcf\data\siraca\race\Race;
use wcf\data\siraca\participation\Participation;
use wcf\data\siraca\participation\ParticipationAction;
use wcf\form\AbstractForm;
use wcf\system\WCF;
use wcf\system\exception\IllegalLinkException;


class ParticipationForm extends AbstractForm {
	
	private $raceID;
	private $race;

	// public function ParticipationForm () {
	// 	throw new IllegalLinkException();
	// }

	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign([
			'raceID' => $this->raceID,
			'race' => $this->race
		]);
	}
	
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['id'])) $this->raceID = intval($_REQUEST['id']);
		$this->checkParameters();
	}

    public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['id'])) $this->raceID = intval($_POST['id']);
		$this->checkParameters();
	}
	
	public function checkParameters() {
		$this->race = new Race($this->raceID);
		
		if (!$this->race->raceID) {
			throw new IllegalLinkException();
		}
	}

	public function save() {
		parent::save();
		
		if($this->race->isParticipant()) return;

		$this->objectAction = new ParticipationAction([], 'create', [
			'data' => array_merge($this->additionalFields, [
				'raceID' => $this->raceID,
				'userID' => WCF::getUser()->userID
			])
        ]);
        
		$this->objectAction->executeAction();
		
		$this->saved();

		// WCF::getTPL()->assign('success', true);
    }

    public function validate() {
		parent::validate();
	}
}