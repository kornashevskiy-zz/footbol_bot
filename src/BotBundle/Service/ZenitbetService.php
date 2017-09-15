<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 15.09.17
 * Time: 10:18
 */

namespace BotBundle\Service;


use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

class ZenitbetService
{
    private $host = 'hub:4444/wd/hub';
    private $url = 'https://zenitbet.com/index2';
    private $data = [
        '4430531',
        'abramovich111'
    ];
    private $dataHandler;

    private $xpath = [
        '//*[@id="loginform"]/div[1]/input',
        '//*[@id="loginform"]/div[2]/input',
        'flash_message' => '//*[@id="box_close_button"]',
        'button' => '//*[@id="header-login-button"]',
        'close_modal' => '//*[@id="box_header_close"]',
        'account' => '//*[@id="header-menu-logged"]/li[1]/a',
        'live_bet' => '//*[@id="header-menu-main"]/li[5]/a',
        'footbol_checkbox' => '//*[@id="live-sid-26"]',
        'download' => '//*[@id="live-index-send"]',
        'body' => '/body'
    ];

    public $title;

    private $match;

    public function __construct($match)
    {
        $this->match = $match;
        $this->dataHandler = new DataHandler();
    }

    public function authorization()
    {
        $driver = RemoteWebDriver::create($this->host, DesiredCapabilities::chrome());
        $driver->get($this->url);
        FeatureContext::setWebDriver($driver);

        for ($i = 0; $i < count($this->data); $i++) {
            IndexPageObject::findElementAndSendKey($this->xpath[$i], $this->data[$i]);
        }

        IndexPageObject::checkAndClick($this->xpath['flash_message']);
        IndexPageObject::findElementAndClick($this->xpath['button']);
        IndexPageObject::checkAndClick($this->xpath['close_modal']);

        $element = IndexPageObject::findElements($this->xpath['account']);

        if (count($element) > 0) {
            return true;
        }

        return false;

    }

    public function goToMatch()
    {
        IndexPageObject::findElementAndClick($this->xpath['live_bet']);
        IndexPageObject::findElementAndClick($this->xpath['footbol_checkbox']);
        IndexPageObject::findElementAndClick($this->xpath['download']);
        sleep(1);

        $html = FeatureContext::getWebDriver()->getPageSource();

        $position = $this->dataHandler->findMatch($html, $this->match);

        foreach ($position as $key => $value) {
            if ($value == null) {
                throw new \Exception('не нашёл игру на Zenitbet');
            }
            if ($key == 'title') {
                $this->title = explode('-', $value);
            }
        }

        $xpath = '//*[@id="'.$position['id'].'"]';

        $click = false;
        while (!$click) {
            try {
                IndexPageObject::findElementAndClick($xpath);
                $click = true;
            } catch (\Exception $e) {

            }
        }

        sleep(1);
//        FeatureContext::getWebDriver()->takeScreenshot('web/gg.png');
    }

    public function setBet()
    {
        $html = FeatureContext::getWebDriver()->getPageSource();
        $betId = $this->dataHandler->findBet($html, $this->title[0]);
        $xpath = '//*[@id="'.$betId.'"]';
        $click = false;
        while (!$click) {
            try {
                IndexPageObject::findElementAndClick($xpath);
                $click = true;
            } catch (\Exception $e) {

            }
        }
        sleep(1);
        FeatureContext::getWebDriver()->takeScreenshot('web/gg.png');
        exit;
    }
}