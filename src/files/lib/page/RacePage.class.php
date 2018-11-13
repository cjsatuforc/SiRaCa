<?php
namespace wcf\page;

use wcf\data\siraca\participation\ViewableParticipationList;
use wcf\data\siraca\race\Race;
use wcf\data\siraca\race\ViewableRace;
use wcf\page\AbstractPage;
use wcf\system\exception\IllegalLinkException;
use wcf\system\WCF;
use wcf\util\DateUtil;

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
        $this->titularList->getConditionBuilder()->add("siraca_participation.waitingList = 0");
        $this->titularList->sqlOrderBy = "position";
        $this->titularList->readObjects(); // TODO regarder quand/pourquoi AbstractPage utilise le readObjectIDs

        $this->waitingList = new ViewableParticipationList($this->race->raceID);
        $this->waitingList->getConditionBuilder()->add("siraca_participation.waitingList = 1");
        $this->waitingList->sqlOrderBy = "position";
        $this->waitingList->readObjects();

        $this->startDateTime = DateUtil::getDateTimeByTimestamp($this->race->startTime);

        // $timezoneObj         = WCF::getUser()->getTimeZone();
        // $this->startDateTime = new \DateTime('now', $timezoneObj);
        // $this->startDateTime->setTimestamp($this->race->startTime);
    }

    public function assignVariables()
    {
        parent::assignVariables();

        $language = WCF::getLanguage();
        $user     = WCF::getUser();

        $titularArray = $this->titularList->getObjects();
        $waitingArray = $this->waitingList->getObjects();

        WCF::getTPL()->assign([
            'race'             => $this->race,
            'titularList'      => $titularArray,
            'waitingList'      => $waitingArray,
            'participantCount' => count($titularArray) + count($waitingArray),
            'startTime'        => DateUtil::format($this->startDateTime, DateUtil::DATE_FORMAT, $language, $user)
            . ' - ' .
            DateUtil::format($this->startDateTime, DateUtil::TIME_FORMAT, $language, $user),
            /*
        TODO : intégrer nom du jour. cf. le date format de calendar.date.dateFormat dans le calendar et la trad FR.
         */
        ]);
    }
}
