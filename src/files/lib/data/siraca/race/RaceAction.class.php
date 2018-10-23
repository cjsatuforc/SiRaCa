<?php
namespace wcf\data\siraca\race;

use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\siraca\participation\ParticipationManager;

class RaceAction extends AbstractDatabaseObjectAction
{
    protected $permissionsDelete = ['mod.siraca.canManageRace'];

    public function delete()
    {
        parent::delete();

        $raceIDs = [];
        foreach ($this->getObjects() as $race) {
            $raceIDs[] = $race->raceID;
        }

        if (!empty($raceIDs)) {
            ParticipationManager::deleteRaceParticipations($raceIDs);
        }
    }
}
