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

    public static function getFormattedDate($timestamp = -1, $format = null)
    {
        if (!$format) {
            $format = "siraca.date.format";
        }

        $date     = self::getNewDate($timestamp);
        $language = WCF::getLanguage();

        $englishDayName   = strtolower($date->format("l"));
        $englishMonthName = strtolower($date->format("F"));

        $dayName   = $language->get("wcf.date.day.$englishDayName");
        $monthName = $language->get("wcf.date.month.$englishMonthName");

        $formattedDate = $date->format($language->get($format));

        $searchDay     = $date->format("l") . "|" . $date->format("D");
        $formattedDate = preg_replace("/$searchDay/", $dayName, $formattedDate);

        $searchMonth   = $date->format("F") . "|" . $date->format("M");
        $formattedDate = preg_replace("/$searchMonth/", $monthName, $formattedDate);

        return $formattedDate;

    }
}
