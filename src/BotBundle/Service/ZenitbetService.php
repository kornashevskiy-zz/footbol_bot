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
    private static $instance;

    private $host = 'hub:4444/wd/hub';
    private $url = 'https://zenitbet.com';
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

    private $html;

    private function __construct()
    {
        $this->dataHandler = new DataHandler();
        FeatureContext::getWebDriver()->get($this->url);
        $this->pageObject = new IndexPageObject();
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param mixed $match
     */
    public function setMatch($match)
    {
        $this->match = $match;
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
        sleep(2);

        $check = false;
        $i = 1;
        while (!$check) {
            $html = FeatureContext::getWebDriver()->getPageSource();
            $check = $this->dataHandler->checkHtml($html, 'table');
            if ($i > 500) {
                throw new \Exception('не могу найти таблицы с матчами на zenitbet');
            }
        }


        $position = $this->dataHandler->findMatch($html, $this->match);

        foreach ($position as $key => $value) {
            if ($value == null) {
                throw new \Exception('не нашёл игру на Zenitbet');
            }

            if ($key == 'title') {
                $title = explode('-', $value);
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

                $i = 1;
                while($click) {
                    $this->html = FeatureContext::getWebDriver()->getPageSource();
                    if ($betId = $this->dataHandler->findBet($this->html, $title[0], true)) {
                        $click = false;
                    }
                    if ($i > 500) {
                        throw new \Exception('не могу найти необходимого элемента, что бы сделать ставку');
                    }
                    $i++;
                }
            }
        }

        return $position['title'];
    }

    public function setBet($title, $countBet)
    {
        $betResult = false;
        $i = 1;
        while(!$betResult) {
            /** @var array $betId */
            $betId = $this->dataHandler->findBet($this->html, $title);

            if (is_array($betId)) {
                $betResult = true;
            }
            if ($i > 500) {
                throw new \Exception('не могу найти необходимого элемента, что бы сделать ставку');
            }
            $i++;
        }


        if (array_key_exists('id', $betId)) {
            $xpath = '//*[@id="'.$betId.'"]';
            $this->click($xpath);
        } elseif (array_key_exists('data-id', $betId)) {
            $xpath = './/tr//a[@data-id='.$betId['data-id'].']';
            $this->pageObject->indexFindElementAndClick($xpath);
            sleep(1);
        } else {
            throw new \Exception('ненадежный путь для того, что бы сделать ставку');
        }


        while($betResult) {
            $html = FeatureContext::getWebDriver()->getPageSource();
            if ($inputId = $this->dataHandler->findInputId($html)) {
                $xpath = '//*[@id="'.$inputId.'"]';
                FeatureContext::getWebDriver()->takeScreenshot('gg.png');
                $this->pageObject->indexFindElementAndSendKey($xpath, $countBet);
                $betResult = false;
            }
        }

        $this->click($this->xpath['doBet']);
        FeatureContext::getWebDriver()->takeScreenshot('gg.png');
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

    public function getSessionId()
    {
        return $this->pageObject->getSessionId();
    }
}