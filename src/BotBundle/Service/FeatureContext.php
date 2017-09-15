<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 14.09.17
 * Time: 15:09
 */

namespace BotBundle\Service;


use Facebook\WebDriver\Remote\RemoteWebDriver;

class FeatureContext
{
    /**@var RemoteWebDriver $webDriver*/
    private static  $webDriver;

    /**
     * @return RemoteWebDriver
     */
    public static function getWebDriver()
    {
        return self::$webDriver;
    }

    /**
     * @param RemoteWebDriver $webDriver
     */
    public static function setWebDriver($webDriver)
    {
        self::$webDriver = $webDriver;
    }


}