<?php
namespace wcf\page;

use wcf\data\siraca\race\Race;
use wcf\page\AbstractPage;
use wcf\system\exception\IllegalLinkException;
use wcf\system\WCF;

class RacePage extends AbstractPage
{
    public $race;
    public $raceID = 0;

    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'race' => $this->race,
        ]);
    }

    public function readData()
    {
        parent::readData();
    }

    public function readParameters()
    {
        parent::readParameters();

        if (isset($_REQUEST['id'])) {
            $this->raceID = intval($_REQUEST['id']);
        }

        $this->race = new Race($this->raceID);
        if (!$this->race->raceID) {
            throw new IllegalLinkException();
        }
    }
}
