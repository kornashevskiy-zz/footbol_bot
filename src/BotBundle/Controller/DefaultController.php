<?php

namespace BotBundle\Controller;

use BotBundle\Form\WilliamType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {


        return $this->render('BotBundle:Default:index.html.twig');
    }
}
