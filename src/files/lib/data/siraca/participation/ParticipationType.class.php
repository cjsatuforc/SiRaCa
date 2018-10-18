<?php

namespace wcf\data\siraca\participation;

class ParticipationType {

    const ABSENCE = 1;
    const PRESENCE_NOT_CONFIRMED = 2;
    const PRESENCE = 3;

    public static function getLangId($type) {
        switch ($type) {
            case self::PRESENCE:
                return 'siraca.participation.type.presence';
            case self::ABSENCE:
                return 'siraca.participation.type.absence';
            case self::PRESENCE_NOT_CONFIRMED:
                return 'siraca.participation.type.presenceNotConfirmed';
        }
    }

    public static function getTypes() {
		return [self::ABSENCE, self::PRESENCE_NOT_CONFIRMED, self::PRESENCE];
    }
}