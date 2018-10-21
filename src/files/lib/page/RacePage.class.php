<?php
namespace wcf\page;

use wcf\data\siraca\participation\ViewableParticipationList;
use wcf\data\siraca\race\Race;
use wcf\data\siraca\race\ViewableRace;
use wcf\page\AbstractPage;
use wcf\system\exception\IllegalLinkException;
use wcf\system\WCF;

class RacePage extends AbstractPage
{
    public $race;
    public $raceID = 0;
    private $participationList;

    public function readParameters()
    {
        parent::readParameters();

        if (isset($_REQUEST['id'])) {
            $this->raceID = intval($_REQUEST['id']);
        }

        $this->race = new ViewableRace(new Race($this->raceID));
        if (!$this->race->raceID) {
            throw new IllegalLinkException();
        }
    }

    public function readData()
    {
        parent::readData();

        $this->participationList = new ViewableParticipationList($this->race->raceID);
        $this->participationList->readObjects(); // TODO regarder quand/pourquoi AbstractPage utilise le readObjectIDs
    }

    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'race'           => $this->race,
            'participations' => $this->participationList->getObjects(),
        ]);
    }
}
