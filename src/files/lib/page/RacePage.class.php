<?php
namespace wcf\page;

use wcf\data\siraca\participation\ListType;
use wcf\data\siraca\participation\ViewableParticipationList;
use wcf\data\siraca\race\Race;
use wcf\data\siraca\race\ViewableRace;
use wcf\page\AbstractPage;
use wcf\system\exception\IllegalLinkException;
use wcf\system\siraca\date\DateUtil;
use wcf\system\WCF;

class RacePage extends AbstractPage
{
    public $race;
    public $raceID = 0;
    private $titularList;
    private $waitingList;
    private $startDateTime;

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

        $this->titularList = new ViewableParticipationList($this->race->raceID);
        $this->titularList->getConditionBuilder()->add("siraca_participation.listType = " . ListType::TITULAR);
        $this->titularList->sqlOrderBy = "position";
        $this->titularList->readObjects();

        $this->waitingList = new ViewableParticipationList($this->race->raceID);
        $this->waitingList->getConditionBuilder()->add("siraca_participation.listType = " . ListType::WAITING);
        $this->waitingList->sqlOrderBy = "position";
        $this->waitingList->readObjects();

        $this->startDateTime = DateUtil::getNewDate($this->race->startTime);
    }

    public function assignVariables()
    {
        parent::assignVariables();

        $titularArray = $this->titularList->getObjects();
        $waitingArray = $this->waitingList->getObjects();

        WCF::getTPL()->assign([
            'race'        => $this->race,
            'titularList' => $titularArray,
            'waitingList' => $waitingArray,
        ]);
    }
}
