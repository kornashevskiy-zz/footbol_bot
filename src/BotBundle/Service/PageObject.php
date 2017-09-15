<?php

namespace BotBundle\Service;


use Facebook\WebDriver\Remote\LocalFileDetector;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverSelect;
use Symfony\Component\Config\Definition\Exception\Exception;

class PageObject
{

    const WAIT_TIMEOUT_IN_SECONDS = 45;
    const INTERVAL_IN_MILLISECOND = 1000;
    const FILES_PATH = __DIR__ . '/../Files/';

    private static $xpathWaitElementBuffer;
    private static $isShow;

    public static function refreshPage()
    {
        FeatureContext::getWebDriver()->navigate()->refresh();
    }


    protected static function checkPrefix($prefix)
    {
        $url = FeatureContext::getWebDriver()->getCurrentURL();
        $checkResult = stristr($url, $prefix);
        if (!$checkResult) {
            throw new Exception('We not on page with prefix "' . $prefix . '". You locate on url: ' . $url);
        }
    }

    protected static function openURLPage($url)
    {
        FeatureContext::getWebDriver()->get($url);
    }

    /**
     * @param $xpath
     * @param bool $isShow
     * @return RemoteWebElement[]
     */
    protected static function findElements($xpath, $isShow=true)
    {
        self::waitShow($xpath,$isShow);
        $elements = FeatureContext::getWebDriver()->findElements(WebDriverBy::xpath($xpath));
        return $elements;
    }

    protected static function findElement($xpath,$isShow=true)
    {
        $elements = self::findElements($xpath,$isShow);
        return $elements[0];
    }

    protected static function findElementAndSendFile($xpath,$filePath){
        $inputAttach = self::findElement($xpath);
        $inputAttach->setFileDetector(new LocalFileDetector());
        $inputAttach->sendKeys($filePath);
    }
    protected static function getTextSelectedOptions($xpath)
    {
        $selectWebElement = self::findElement($xpath);
        $select = new WebDriverSelect($selectWebElement);
        $firstSelectedOptionWebElement = $select->getFirstSelectedOption();
        $textSelectedOption = $firstSelectedOptionWebElement->getText();
        return $textSelectedOption;
    }

    protected static function findElementAndClick($xpath)
    {
        self::waitShow($xpath);
        $elements = FeatureContext::getWebDriver()->findElements(WebDriverBy::xpath($xpath));
        self::clickOnElement($elements[0]);
    }


    protected static function findElementAndSendKey($xpath, $data, $clearInput = true)
    {
        self::waitShow($xpath);
        $elements = FeatureContext::getWebDriver()->findElements(WebDriverBy::xpath($xpath));
        $circle = 0;
        while (true) {
            try {
                if ($clearInput) {
                    $elements[0]->clear();
                }
                $elements[0]->sendKeys($data);
                break;
            } catch (\Exception $e) {
                if ($circle > 30) {
                    throw new Exception('Elements not be click. Xpath: ' . $xpath);
                }
                sleep(2);
                $circle++;
                continue;
            }
        }
    }

    protected static function inspectTheElements($xpath)
    {
        $elements = FeatureContext::getWebDriver()->findElements(WebDriverBy::xpath($xpath));
        $countElements = count($elements);
        if ($countElements > 0) {
            throw new Exception("On the page found elements by xpath: " . $xpath);
        }
    }

    protected static function clickOnElement(RemoteWebElement $webElement)
    {
        $circle = 0;
        while (true) {
            try {
                $webElement->click();
                break;
            } catch (\Exception $e) {
                if ($circle > 30) {
                    throw new Exception('Elements not be click');
                }
                sleep(2);
                $circle++;
                continue;
            }
        }
    }

    /**
     * @param $xpath
     * @param bool $isShow
     * @throws \Exception
     */
    private static function waitShow($xpath,$isShow = true)
    {
        self::$xpathWaitElementBuffer = $xpath;
        self::$isShow = $isShow;


//        print "[" . date('H:i:s') . "] Find element start by xpath: '".$xpath."'  On url:  " . FeatureContext::getWebDriver()->getCurrentURL().PHP_EOL;
        try {
            FeatureContext::getWebDriver()->wait(self::WAIT_TIMEOUT_IN_SECONDS, self::INTERVAL_IN_MILLISECOND)->until(function ($driver) {
                /**@var RemoteWebDriver $driver */
                $elements = $driver->findElements(WebDriverBy::xpath(self::$xpathWaitElementBuffer));
                if(self::$isShow){
                    return (
                        count($elements) > 0 &&
                        $elements[0]->isDisplayed()&&
                        $elements[0]->isEnabled()
                    );
                }else{
                return (count($elements) > 0);
                }
            });
//            print "[" . date('H:i:s') . "] Find element stop" . PHP_EOL;
        } catch (\Exception $e) {
            throw new \Exception("[" . date('H:i:s') . "] File not be find or element not display with xpath:" . $xpath . " \nby url: " . FeatureContext::getWebDriver()->getCurrentURL() . PHP_EOL . PHP_EOL . $e->getMessage() . PHP_EOL);
        }
    }

    public static function waitShowElement($xpath)
    {
        self::waitShow($xpath);
    }

    public static function downloadByUrl($url){
        $coookies = FeatureContext::getWebDriver()->manage()->getCookies();
        $coookiesString  = "Cookie: ";

        foreach ($coookies as $cookie){
            $name = $cookie->getName();
            $value = $cookie->getValue();
            $coookiesString = $coookiesString. $name."=".$value.";";

        }

        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($coookiesString));
        $file = curl_exec($ch);
        curl_close($ch);

        return $file;
    }

    protected static function executeJsScript($scriptString){
        $result = FeatureContext::getWebDriver()->executeScript($scriptString);
        return $result;
    }

    public static function getWebDriver(){
        return FeatureContext::getWebDriver();
    }

    public static function pressKeyboardKey($key){
        FeatureContext::getWebDriver()->getKeyboard()->sendKeys($key);
    }
}