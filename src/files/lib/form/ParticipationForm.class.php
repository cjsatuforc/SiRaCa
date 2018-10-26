<?php
namespace wcf\form;

use wcf\data\siraca\participation\ParticipationAction;
use wcf\data\siraca\participation\ParticipationManager;
use wcf\data\siraca\participation\ParticipationType;
use wcf\data\siraca\race\Race;
use wcf\form\AbstractForm;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\UserInputException;
use wcf\system\page\PageLocationManager;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;
use wcf\util\HeaderUtil;

class ParticipationForm extends AbstractForm
{

    private $race;
    private $participation;
    private $newParticipationType;

    public $neededPermissions = ['user.siraca.canParticipateRace'];

    public function readParameters()
    {
        parent::readParameters();

        $raceID = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

        $this->race = new Race($raceID);

        if (!$this->race->raceID) {
            throw new IllegalLinkException();
        }
    }

    public function readFormParameters()
    {
        parent::readFormParameters();

        if (isset($_POST['participationType'])) {
            $this->newParticipationType = intval($_POST['participationType']);
        }

    }

    public function validate()
    {
        parent::validate();

        $this->participation = ParticipationManager::getUserParticipation($this->race->raceID);

        if ($this->newParticipationType == $this->participation->type) {
            throw new UserInputException("participationType", "noChange");
        }
    }

    public function save()
    {
        parent::save();

        if ($this->newParticipationType == ParticipationType::ABSENCE) {
            $action = 'delete';
        } else {
            $action = $this->participation->participationID ? 'update' : 'create';
        }

        switch ($action) {
            case 'create':
                $objects = [];
                $row     = [
                    'raceID' => $this->race->raceID,
                    'userID' => WCF::getUser()->userID,
                    'type'   => $this->newParticipationType,
                ];
                break;

            case 'update':
                $objects = [$this->participation->getDecoratedObject()];
                $row     = ['type' => $this->newParticipationType];
                break;

            case 'delete':
                $objects = [$this->participation->getDecoratedObject()];
                $row     = [];
        }

        $this->objectAction = new ParticipationAction($objects, $action, [
            'data' => array_merge($this->additionalFields, $row),
        ]);

        $this->objectAction->executeAction();

        $this->saved();

        HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Race', [
            'object' => $this->race,
        ]));
    }

    public function readData()
    {
        parent::readData();

        $this->participation = ParticipationManager::getUserParticipation($this->race->raceID);
        PageLocationManager::getInstance()->addParentLocation('fr.chatcureuil.siraca.Race', $this->race->raceID, $this->race);
    }

    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'race'               => $this->race,
            'participation'      => $this->participation,
            'participationTypes' => ParticipationType::getTypes(),
        ]);
    }
}
