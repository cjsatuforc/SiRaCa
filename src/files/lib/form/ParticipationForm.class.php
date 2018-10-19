<?php
namespace wcf\form;

use wcf\data\siraca\participation\Participation;
use wcf\data\siraca\participation\ParticipationAction;
use wcf\data\siraca\participation\ParticipationType;
use wcf\data\siraca\race\Race;
use wcf\form\AbstractForm;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\UserInputException;
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

        $this->participation = Participation::getUserParticipation($this->race->raceID);

        if ($this->newParticipationType == $this->participation->type) {
            throw new UserInputException("participationType", "noChange");
        }
        // TODO Gérer aussi l'erreur dans le formulaire, cf. création de course et titre vide
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
                $objects = [$this->participation];
                $row     = ['type' => $this->newParticipationType];
                break;
            case 'delete':
                $objects = [$this->participation];
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

        $this->participation = Participation::getUserParticipation($this->race->raceID);
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
