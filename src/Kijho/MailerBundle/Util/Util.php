<?php

namespace Kijho\MailerBundle\Util;

/**
 * Description of Util
 *
 * @author Cesar Giraldo - Kijho Technologies <cnaranjo@kijho.com> 10/11/2015
 */
class Util {

    public static function getCurrentDate($zone = 'America/Bogota') {
        $timezone = new \DateTimeZone($zone);
        $datetime = new \DateTime('now');
        $datetime->setTimezone($timezone);
        return $datetime;
    }
}

