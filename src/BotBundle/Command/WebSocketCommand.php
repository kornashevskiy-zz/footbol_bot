<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.09.17
 * Time: 13:21
 */

namespace BotBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WebSocketCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName('app:start.socket.server');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

    }
}