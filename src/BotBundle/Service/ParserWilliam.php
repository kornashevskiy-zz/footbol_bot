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

    private $pageObject;

    private $clickElement = '//*[@id="bottomContainer"]/div/div[2]/nav/ul/li[3]/a';

    public function __construct($url)
    {
        $this->url = $url;
        $this->pageObject = new IndexPageObject();
    }

    /**
     * @return RemoteWebDriver
     */
    public function connect()
    {
        $driver = RemoteWebDriver::create($this->host, DesiredCapabilities::chrome());
        $driver->get($this->url);
        FeatureContext::setWebDriver($driver);
        IndexPageObject::findElementAndClick($this->clickElement);

        return $driver;
    }

    public function getContent()
    {
        $elements = IndexPageObject::findElements(self::ELEMENT);

        if (count($elements) == 0) {
            print 'test';
            $this->getContent();
        }

        $text = null;
        foreach ($elements as $element) {
            $text = $element->getText();
            break;
        }
        return $text;
    }
}