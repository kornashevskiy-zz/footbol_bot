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
    public static function indexFindElements($xpath, $isShow=true){

        return parent::findElements($xpath, false);
    }

    public function indexFindElementAndClickByCss($selector)
    {
        parent::findElementAndClick('', 'css', $selector);
    }

    public function indexFindElementAndClick($xpath)
    {
        parent::findElementAndClick($xpath);
    }

    public static function indexFindElementAndSendKey($xpath, $data, $clearInput = true)
    {
        parent::findElementAndSendKey($xpath, $data, $clearInput);
    }

    public static function indexCheckAndClick($xpath)
    {
        $flash_message = PageObject::findElements($xpath);

        if (count($flash_message) > 0) {
            PageObject::findElementAndClick($xpath);
        }
        return;
    }

    public function indexGetDriver()
    {
        FeatureContext::getWebDriver();
    }

    public function getSessionId()
    {
        return parent::getSessionId();
    }
}