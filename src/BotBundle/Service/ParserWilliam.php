<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 14.09.17
 * Time: 15:37
 */

namespace BotBundle\Service;


use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

class ParserWilliam
{
    private $url;

    const ELEMENT ='//*[@id="box_commentaries"]/ul/li/span[3]';

    private $host = 'hub:4444/wd/hub';

    private $clickElement = '//*[@id="bottomContainer"]/div/div[2]/nav/ul/li[3]/a';

    private $title = [
        '//*[@id="topContainer"]/div[1]/div/div[1]',
        '//*[@id="topContainer"]/div[1]/div/div[3]'
    ];

    /**
     * @var RemoteWebDriver
     */
    private $pageObject;

    public function __construct($url)
    {
        $this->url = $url;
        $driver = RemoteWebDriver::create($this->host, DesiredCapabilities::chrome());
        $driver->get($this->url);
        $this->pageObject = new IndexPageObject($driver);
    }

    public function connect()
    {
        $html = $this->pageObject->indexGetDriver()->getPageSource();
        $this->pageObject->indexFindElementAndClick($this->clickElement);

        return;
    }

    public function getContent()
    {
        $elements = $this->pageObject->indexFindElements(self::ELEMENT);

        if (count($elements) == 0) {
            $this->getContent();
        }

        $text = null;
        foreach ($elements as $element) {
            $text = $element->getText();
            break;
        }
        return $text;
    }

    public function getTitle()
    {
        $elementsA = $this->pageObject->indexFindElements($this->title[0]);
        $elementsB = $this->pageObject->indexFindElements($this->title[1]);

        if (count($elementsA) == 0) {
            $this->getTitle();
        }

        $textA = null;
        foreach ($elementsA as $element) {
            $textA = $element->getText();
            break;
        }

        if (count($elementsA) == 0) {
            $this->getTitle();
        }

        $textB = null;
        foreach ($elementsB as $element) {
            $textB = $element->getText();
            break;
        }


        return trim($textA.' v '.$textB);
    }
}