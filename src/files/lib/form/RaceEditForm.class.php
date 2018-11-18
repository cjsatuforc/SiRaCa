<?php
namespace wcf\form;

use wcf\data\siraca\participation\ParticipationManager;
use wcf\data\siraca\race\Race;
use wcf\data\siraca\race\RaceAction;
use wcf\system\page\PageLocationManager;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;
use wcf\util\HeaderUtil;

class RaceEditForm extends RaceAddForm
{
    private $race;

    public function readParameters()
    {
        parent::readParameters();

        $raceID = 0;

        if (isset($_REQUEST['id'])) {
            $raceID = intval($_REQUEST['id']);
        }

        $this->race = new Race($raceID);
        if (!$this->race->raceID) {
            throw new IllegalLinkException();
        }
    }

    public function readData()
    {
        parent::readData();

        if (empty($_POST)) {
            $this->title = $this->race->title;

            $timezoneObj         = WCF::getUser()->getTimeZone();
            $this->startDateTime = new \DateTime('now', $timezoneObj);
            $this->startDateTime->setTimestamp($this->race->startTime);

            $this->availableSlots = $this->race->availableSlots;
        }

        PageLocationManager::getInstance()->addParentLocation('fr.chatcureuil.siraca.page.Race', $this->race->raceID, $this->race);
    }

    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'action' => 'edit',
            'race'   => $this->race,
        ]);
    }

    public function save()
    {
        AbstractForm::save();

        $action = new RaceAction([$this->race], 'update', [
            'data' => array_merge($this->additionalFields, [
                'title'          => $this->title,
                'startTime'      => $this->startDateTime->getTimestamp(),
                'availableSlots' => $this->availableSlots,
            ]),
        ]);

        $action->executeAction();

        if ($this->availableSlots != $this->race->availableSlots) {
            ParticipationManager::computePositions($this->race, $this->availableSlots);
        }

        $this->saved();

        HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Race', [
            'object' => $this->race,
        ]));
    }
}
