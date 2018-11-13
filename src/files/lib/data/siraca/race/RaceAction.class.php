<?php
namespace wcf\data\siraca\race;

use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\siraca\participation\ParticipationUtil;

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
            ParticipationUtil::deleteRaceParticipations($raceIDs);
        }
    }
}
