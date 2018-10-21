<?php
namespace wcf\data\siraca\participation;

class ViewableParticipationList extends ParticipationList
{
    public $decoratorClassName = ViewableParticipation::class;

    public function __construct($raceID)
    {
        parent::__construct();

        $this->getConditionBuilder()->add("siraca_participation.raceID = $raceID");
    }
}
