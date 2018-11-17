<?php
namespace wcf\form;

use wcf\data\siraca\participation\EstimatedPosition;
use wcf\data\siraca\participation\ParticipationManager;
use wcf\data\siraca\participation\ParticipationType;
use wcf\data\siraca\participation\ParticipationUtil;
use wcf\data\siraca\race\Race;
use wcf\data\siraca\race\ViewableRace;
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

        $this->race = new ViewableRace(new Race($raceID));

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

        $this->participation = ParticipationUtil::getUserParticipation($this->race->raceID);

        if ($this->newParticipationType == $this->participation->type) {
            throw new UserInputException("participationType", "noChange");
        }
    }

    public function save()
    {
        parent::save();

        ParticipationManager::setParticipation($this->race, WCF::getUser(), $this->participation->getDecoratedObject(), $this->newParticipationType);

        $this->saved();

        HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Race', [
            'object' => $this->race,
        ]));
    }

    public function readData()
    {
        parent::readData();

        $this->participation = ParticipationUtil::getUserParticipation($this->race->raceID);
        PageLocationManager::getInstance()->addParentLocation('fr.chatcureuil.siraca.page.Race', $this->race->raceID, $this->race->getDecoratedObject());
    }

    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'race'                         => $this->race,
            'participation'                => $this->participation,
            'participationTypes'           => ParticipationType::getTypes(),
            'estimatedPositions'           => EstimatedPosition::estimateParticipationChangePositions($this->race, $this->participation),
            // 'isTitularFullIfSwitchingToUnconfirmed' => EstimatedPosition::isTitularFullIfSwitchingToUnconfirmed($this->race, $this->participation),
            'unconfirmedParticipationType' => ParticipationType::PRESENCE_NOT_CONFIRMED,
            'absenceParticipationType'     => ParticipationType::ABSENCE,
        ]);
    }
}
