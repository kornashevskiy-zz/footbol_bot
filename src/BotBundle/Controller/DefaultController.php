<?php

namespace BotBundle\Controller;

use BotBundle\Form\IndexType;
use BotBundle\Service\CompareService;
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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(IndexType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            try {
//                $arrayTitles = CompareService::compareWilliamsAndZenitbet($data);
                $arrayTitles = [
                    'Матч на williamhill.com' => 'text',
                    'Матч на zenitbet.com' => 'text',
                ];
                return $this->render('BotBundle:Default:index.html.twig', [
                    'form' => $form->createView(),
                    'titles' => $arrayTitles,
                    'data' => json_encode($data),
                    'modal' => true,
                ]);

            } catch (\Exception $e) {
                return $this->render('BotBundle:Default:index.html.twig', [
                    'form' => $form->createView(),
                    'error' => $e->getMessage().'<br/>'.$e->getLine().'<br/>'.$e->getLine(),
                    'modal' => true,
                ]);
            }


        }

        return $this->render('BotBundle:Default:index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/ajax", name="ajax")
     */
    public function compareAction()
    {
        for($i = 0; $i < 10; $i++) {
            sleep(1);
            echo 'text';
            ob_flush();
            flush();
        }

        return new Response();
    }

    /**
     * @Route("info", name="info")
     */
    public function iniAction()
    {
//        for($i = 0; $i < 10; $i++) {
//            sleep(1);
//            echo 'text';
//            ob_flush();
//            flush();
//        }
        echo phpinfo();
        return new Response();
    }
}
