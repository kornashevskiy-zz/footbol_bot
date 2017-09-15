<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 14.09.17
 * Time: 16:37
 */

namespace BotBundle\Command;


use BotBundle\Service\LinkHandler;
use BotBundle\Service\ParserWilliam;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WilliamCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName('app:start');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $linkHandler = new LinkHandler();
            $link = $linkHandler->changeUrl('http://sports.williamhill.com/betting/ru-ru/%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB/OB_EV11706499/krabi-fc-u19-v-yala-sports-association-u19');
            $parser = new ParserWilliam($link);
            $parser->connect();

            $goal = false;
            $str = '';

            while (!$goal) {
                try {
                    $message = $parser->getContent();
                } catch (\Exception $e) {
//                    print $e->getMessage().' '.$e->getFile().' '.$e->getLine();
                }


                if ($message != $str) {
                    $str = $message;
                    print $message."\n";
                }
                if (strpos($message, 'гол') != false || strpos($message, 'гол') == 0) {
                    $goal = true;
                    print 'exit from while';
                }
            }
            print 'exit out of while';

        } catch (\Exception $e) {
            $e->getMessage();
            $line = $e->getLine();
            $file = $e->getFile();
        }

        print 'exit';
    }
}