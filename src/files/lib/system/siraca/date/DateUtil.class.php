<?php
namespace wcf\system\siraca\date;

class DateUtil
{
    const MIN_YEAR = 1970;
    const MAX_YEAR = 2037;

    public static function getCurrentYear()
    {
        $date = new \DateTime('@' . TIME_NOW);
        $date->setTimezone(WCF::getUser()->getTimeZone());
        return $date->format('Y');
    }

    public static function getCurrentMonth()
    {
        $date = new \DateTime('@' . TIME_NOW);
        $date->setTimezone(WCF::getUser()->getTimeZone());
        return $date->format('n');
    }
}
