<?php
namespace wcf\data\siraca\participation;

class ListType
{
    const TITULAR = 1;
    const WAITING = 2;

    public static function getOtherType($listType)
    {
        switch ($listType) {
            case self::TITULAR:return self::WAITING;
            case self::WAITING:return self::TITULAR;
        }
    }
}
