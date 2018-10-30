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
    private $participationList;
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

        $this->participationList = new ViewableParticipationList($this->race->raceID);
        $this->participationList->readObjects(); // TODO regarder quand/pourquoi AbstractPage utilise le readObjectIDs

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

        WCF::getTPL()->assign([
            'race'           => $this->race,
            'participations' => $this->participationList->getObjects(),
            'startTime'      => DateUtil::format($this->startDateTime, DateUtil::DATE_FORMAT, $language, $user)
            . ' - ' .
            DateUtil::format($this->startDateTime, DateUtil::TIME_FORMAT, $language, $user),
            /*
        TODO : intégrer nom du jour. cf. le date format de calendar.date.dateFormat dans le calendar et la trad FR.
         */
        ]);
    }
}
