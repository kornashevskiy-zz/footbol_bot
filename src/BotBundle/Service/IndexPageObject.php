<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 14.09.17
 * Time: 15:07
 */

namespace BotBundle\Service;


class IndexPageObject extends PageObject
{

    public static function downloadByUrl($url)
    {

    }

    public static function findElements($xpath, $isShow=true){

        return parent::findElements($xpath, false);
    }

    public static function findElementAndClick($xpath)
    {
        parent::findElementAndClick($xpath);
    }

    public static function findElementAndSendKey($xpath, $data, $clearInput = true)
    {
        parent::findElementAndSendKey($xpath, $data, $clearInput = true);
    }

    public static function checkAndClick($xpath)
    {
        $flash_message = PageObject::findElements($xpath);

        if (count($flash_message) > 0) {
            PageObject::findElementAndClick($xpath);
        }
        return;
    }
}