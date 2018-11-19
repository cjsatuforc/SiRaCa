<?php
namespace wcf\system\siraca\date;

use wcf\system\WCF;

class DateUtil
{
    const MIN_YEAR = 1970;
    const MAX_YEAR = 2037;

    public static function getCurrentYear()
    {
        return intval(self::getNewDate()->format('Y'));
    }

    public static function getCurrentMonth()
    {
        return intval(self::getNewDate()->format('n'));
    }

    public static function getNewDate($timestamp = -1)
    {
        if ($timestamp > -1) {
            $date = new \DateTime("@$timestamp");
            $date->setTimezone(WCF::getUser()->getTimeZone());
            return $date;
        }

        return new \DateTime(null, WCF::getUser()->getTimeZone());
    }

    public static function getTimestamp()
    {
        return (new \DateTime())->getTimestamp();
    }
}
