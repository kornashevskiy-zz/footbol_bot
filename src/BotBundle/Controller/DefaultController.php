<?php

namespace BotBundle\Controller;

use BotBundle\Service\FeatureContext;
use BotBundle\Service\IndexPageObject;
use BotBundle\Service\LinkHandler;
use BotBundle\Service\ParserWilliam;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DomCrawler\Crawler;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
//        try {
//            $linkHandler = new LinkHandler();
//            $link = $linkHandler->changeUrl('http://sports.williamhill.com/betting/ru-ru/football/OB_EV11657004');
//            $parser = new ParserWilliam($link);
//            $parser->connect();
//
//            $goal = false;
//            $str = '';
//
//            while (!$goal) {
//                $message = $parser->getContent();
//
//                if ($message != $str) {
//                    $str = $message;
//                    echo $message;
//                }
//                if (strpos($message, 'гол') != false && strpos($message, 'гол') == 0) {
//                    $goal = true;
//                }
//            }
//
//
//        } catch (\Exception $e) {
//            $e->getMessage();
//            $line = $e->getLine();
//            $file = $e->getFile();
//        }


        return $this->render('BotBundle:Default:test.html.twig');
    }
}
