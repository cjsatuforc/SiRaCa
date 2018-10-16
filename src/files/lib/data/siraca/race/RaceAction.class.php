<?php
namespace wcf\data\siraca\race;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\exception\PermissionDeniedException;
// use wcf\data\siraca\race\RaceEditor;

class RaceAction extends AbstractDatabaseObjectAction {
    
    // protected $className = RaceEditor::class;
    
    protected $permissionsCreate = ['mod.siraca.canManageRace'];
    protected $permissionsDelete = ['mod.siraca.canManageRace'];
    protected $permissionsUpdate = ['mod.siraca.canManageRace'];
    
    public function executeAction() {
        $this->validateAction(); // TODO C'est vraiment comme ça qu'on doit faire ?
        parent::executeAction();
    }
}