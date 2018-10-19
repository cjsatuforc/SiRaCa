<?php

namespace wcf\data\siraca\participation;

class ParticipationType
{
    const ABSENCE                = 1;
    const PRESENCE_NOT_CONFIRMED = 2;
    const PRESENCE               = 3;

    private static $absenceInstance      = null;
    private static $notConfirmedInstance = null;
    private static $presenceInstance     = null;

    private $type            = 0;
    private $typeLangId      = '';
    private $longTextLangId  = '';
    private $shortTextLangId = '';

    protected function __construct($type, $typeLangId, $longTextLangId, $shortTextLangId)
    {
        $this->type            = $type;
        $this->typeLangId      = $typeLangId;
        $this->longTextLangId  = $longTextLangId;
        $this->shortTextLangId = $shortTextLangId;
    }

    public function __get($propertyName)
    {
        return $this->$propertyName;
    }

    public static function getTypes()
    {
        if (!self::$absenceInstance) {
            self::createInstances();
        }

        return [
            self::ABSENCE                => self::$absenceInstance,
            self::PRESENCE_NOT_CONFIRMED => self::$notConfirmedInstance,
            self::PRESENCE               => self::$presenceInstance,
        ];
    }

    private static function createInstances()
    {
        self::$absenceInstance = new ParticipationType(
            self::ABSENCE,
            'siraca.participation.type.absence',
            'siraca.participation.registration.notRegistered',
            'siraca.participation.registration.notRegisteredShort');

        self::$notConfirmedInstance = new ParticipationType(
            self::PRESENCE_NOT_CONFIRMED,
            'siraca.participation.type.presenceNotConfirmed',
            'siraca.participation.registration.notConfirmed',
            'siraca.participation.registration.notConfirmedShort');

        self::$presenceInstance = new ParticipationType(
            self::PRESENCE,
            'siraca.participation.type.presence',
            'siraca.participation.registration.registered',
            'siraca.participation.registration.registeredShort');
    }
}
