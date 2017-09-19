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
        'body' => '/body',
        'doBet' => '//*[@id="basket-dobet"]'
    ];

    public $title;

    private $match;

    private $pageObject;

    public function __construct($match)
    {
        $this->match = $match;
        $this->dataHandler = new DataHandler();
        $driver = RemoteWebDriver::create($this->host, DesiredCapabilities::chrome());
        $driver->get($this->url);
        $this->pageObject = new IndexPageObject($driver);
    }

    public function authorization(array $data)
    {
        for ($i = 0; $i < 2; $i++) {
            $this->pageObject->indexFindElementAndSendKey($this->xpath[$i], $data[$i]);
        }

        $this->pageObject->indexCheckAndClick($this->xpath['flash_message']);
        $this->pageObject->indexFindElementAndClick($this->xpath['button']);
        $this->pageObject->indexCheckAndClick($this->xpath['close_modal']);

        $element = $this->pageObject->indexFindElements($this->xpath['account']);

        if (count($element) > 0) {
            return true;
        }

        return false;

    }

    public function goToMatch()
    {
        $this->pageObject->indexFindElementAndClick($this->xpath['live_bet']);
        $this->pageObject->indexFindElementAndClick($this->xpath['footbol_checkbox']);
        $this->pageObject->indexFindElementAndClick($this->xpath['download']);
        sleep(1);

        $html = $this->pageObject->indexGetDriver()->getPageSource();

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
                $this->pageObject->indexFindElementAndClick($xpath);
                $click = true;
            } catch (\Exception $e) {
                $massage = $e->getMessage();

            }
        }

        sleep(1);

        return $position['title'];
    }

    public function setBet()
    {
        $html = $this->pageObject->indexGetDriver()->getPageSource();
        $betId = $this->dataHandler->findBet($html, $this->title[0]);
        $xpath = '//*[@id="'.$betId.'"]';
        $this->click($xpath);

        sleep(1);
        $html = $this->pageObject->indexGetDriver()->getPageSource();
        $inputId = $this->dataHandler->findInputId($html);
        $xpath = '//*[@id="'.$inputId.'"]';
        $this->pageObject->indexFindElementAndSendKey($xpath, 1);
//        $this->click($this->xpath['doBet']);
        $this->pageObject->indexGetDriver()->takeScreenshot('gg.png');
    }

    private function click($xpath)
    {
        $click = false;
        while (!$click) {
            try {
                $this->pageObject->indexFindElementAndClick($xpath);
                $click = true;
            } catch (\Exception $e) {

            }
        }
    }
}