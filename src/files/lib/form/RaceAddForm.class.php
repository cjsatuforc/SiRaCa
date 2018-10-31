<?php
namespace wcf\form;

use wcf\data\siraca\race\RaceAction;
use wcf\form\AbstractForm;
use wcf\system\exception\UserInputException;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;
use wcf\util\HeaderUtil;
use wcf\util\StringUtil;

class RaceAddForm extends AbstractForm
{
    public $neededPermissions = ['mod.siraca.canManageRace'];

    public $title          = '';
    public $startTime      = null;
    public $startDateTime  = null;
    public $availableSlots = null;

    public function readFormParameters()
    {
        parent::readFormParameters();

        if (isset($_POST['title'])) {
            $this->title = StringUtil::trim($_POST['title']);
        }

        if (isset($_POST['startTime'])) {
            $this->startTime = $_POST['startTime'];

            $timezoneObj         = WCF::getUser()->getTimeZone();
            $this->startDateTime = \DateTime::createFromFormat('Y-m-d\TH:i:s', $this->startTime, $timezoneObj);
        }

        if (isset($_POST['availableSlots'])) {
            $this->availableSlots = intval($_POST['availableSlots']);
        }
    }

    public function validate()
    {
        parent::validate();

        // TITLE
        if (empty($this->title)) {
            throw new UserInputException('title');
        }
        if (mb_strlen($this->title) > 255) {
            throw new UserInputException('title', 'tooLong');
        }

        // AVAILABLE SLOTS
        if ($this->availableSlots < 1) {
            throw new UserInputException('availableSlots', 'tooLow');
        } else if ($this->availableSlots > PHP_INT_MAX) {
            throw new UserInputException('availableSlots', 'tooHigh');
        }

        // START TIME
        if (empty($this->startTime)) {
            throw new UserInputException('startTime');
        } else if ($this->startDateTime === false) {
            throw new UserInputException('startTime', 'invalid');
        } else {
            $currentTimestamp = (new \DateTime())->getTimestamp();
            if ($this->startDateTime->getTimestamp() < $currentTimestamp) {
                throw new UserInputException('startTime', 'past');
            }
        }
    }

    public function save()
    {
        parent::save();

        $this->objectAction = new RaceAction([], 'create', [
            'data' => array_merge($this->additionalFields, [
                'title'          => $this->title,
                'startTime'      => $this->startDateTime->getTimestamp(),
                'availableSlots' => $this->availableSlots,
            ]),
        ]);

        $race = $this->objectAction->executeAction()['returnValues'];

        $this->saved();

        HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Race', [
            'object' => $race,
        ]));
    }

    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'action'         => 'add',
            'title'          => $this->title,
            'startTime'      => $this->startDateTime != null ? $this->startDateTime->format('c') : null,
            'availableSlots' => $this->availableSlots,
        ]);
    }

}
