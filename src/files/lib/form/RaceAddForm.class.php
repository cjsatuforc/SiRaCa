<?php
namespace wcf\form;

use wcf\data\siraca\race\RaceAction;
use wcf\form\AbstractForm;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;
use wcf\util\StringUtil;

class RaceAddForm extends AbstractForm
{
    public $neededPermissions = ['mod.siraca.canManageRace'];

    public $title = '';

    public function readFormParameters()
    {
        parent::readFormParameters();

        if (isset($_POST['title'])) {
            $this->title = StringUtil::trim($_POST['title']);
        }

    }

    public function validate()
    {
        parent::validate();

        if (empty($this->title)) {
            throw new UserInputException('title');
        }
        if (mb_strlen($this->title) > 255) {
            throw new UserInputException('title', 'tooLong');
        }
    }

    public function save()
    {
        parent::save();

        $this->objectAction = new RaceAction([], 'create', [
            'data' => array_merge($this->additionalFields, [
                'title' => $this->title,
            ]),
        ]);

        $this->objectAction->executeAction();

        $this->saved();

        $this->title = '';

        // show success message
        WCF::getTPL()->assign('success', true);
    }

    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'action' => 'add',
            'title'  => $this->title,
        ]);
    }

}
