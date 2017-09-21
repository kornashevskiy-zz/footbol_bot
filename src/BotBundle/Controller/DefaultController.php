<?php

namespace BotBundle\Controller;

use BotBundle\Form\IndexType;
use BotBundle\Service\CompareService;
use BotBundle\Service\DataHandler;
use BotBundle\Service\FeatureContext;
use BotBundle\Service\ParserWilliam;
use BotBundle\Service\ZenitbetService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
                $arrayTitles = CompareService::compareWilliamsAndZenitbet($data);

                /** for testing **/
                /** end testing code */

                return $this->render('BotBundle:Default:index.html.twig', [
                    'form' => $form->createView(),
                    'titles' => $arrayTitles,
                    'data' => json_encode($data),
                    'modal' => true,
                ]);

            } catch (\Exception $e) {
                return $this->render('BotBundle:Default:index.html.twig', [
                    'form' => $form->createView(),
                    'error' => $e->getMessage()." ".$e->getFile().'<br/>'.$e->getLine(),
                    'modal' => true,
                ]);
            }


        }

        return $this->render('BotBundle:Default:index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/ajax/{data}", name="ajax", requirements={"data": ".*"})
     */
    public function compareAction($data)
    {
        try {
            $content = json_decode($data, true);

            $titles = CompareService::compareWilliamsAndZenitbet($content);

            $williamArray = explode(' v ', $titles['Матч на williamhill.com']);
            $zenitmArray = explode('-', $titles['Матч на zenitbet.com']);

            if (is_array($williamArray) && is_array($zenitmArray)) {
                $parser = ParserWilliam::getInstance();
                $zenitService = ZenitbetService::getInstance();

                FeatureContext::getWebDriver()->executeScript('window.open()');
                $tabs = FeatureContext::getWebDriver()->getWindowHandles();
                FeatureContext::getWebDriver()->switchTo()->window($tabs[0]);

                $goal = false;
                $str = '';

                while (!$goal) {
                    try {
                        $parser->photo();
                        $message = $parser->getContent();
                    } catch (\Exception $e) {
//                    print $e->getMessage().' '.$e->getFile().' '.$e->getLine();
                    }

                    $test = strpos($message, 'гол') != false;
                    $test = strpos($message, 'гол') == 0;
                    if (strpos($message, 'гол') != false || strpos($message, 'гол') === 0) {
                        $start = microtime(true);
                        for ($i = 0; $i <= count($williamArray); $i++) {
                            if (strpos($message, $williamArray[$i]) != false || strpos($message, $williamArray[$i]) == 0) {
                                $zenitService->setBet(trim($zenitmArray[$i]), $content['count_bet']);
                                $end = microtime(true);
                                $speed = bcsub($end, $start, 4);
                                $str = DataHandler::addPoint($message);
                                echo "<b>".$str."</b><br>";
                                echo 'ставка сделана за '.$speed.' секунд';
                            }
                        }
                        $goal = true;
                    }


                    if ($message != $str) {
                        $str = $message;
                        $newStr = DataHandler::addPoint($message);
                        echo $newStr."<br>";
                    }
                }

            } else {
                throw new \Exception('Разделитель в заголовке матча не соответствует шаблону: William(match v match), zenit(match - match)');
            }
        } catch (\Exception $e) {
            echo "<b>".$e->getMessage()."</b><br>";
        }

        return new Response();
    }

    /**
     * @Route("test", name="info")
     */
    public function iniAction()
    {
        return $this->render('@Bot/Default/test.html.twig');
    }
}
