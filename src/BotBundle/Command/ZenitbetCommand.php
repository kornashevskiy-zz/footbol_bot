<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 15.09.17
 * Time: 10:49
 */

namespace BotBundle\Command;


use BotBundle\Service\Connect;
use BotBundle\Service\ZenitbetService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ZenitbetCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName('app:login');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $zenitService = new ZenitbetService('102');

        if ($zenitService->authorization()) {
            $zenitService->goToMatch();
            $zenitService->setBet();
        }
    }
}