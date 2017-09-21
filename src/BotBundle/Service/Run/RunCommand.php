<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.09.17
 * Time: 12:53
 */

namespace BotBundle\Service\Run;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;

class RunCommand
{
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function run($command)
    {
        $aplication = new Application($this->kernel);
        $aplication->setAutoExit(false);

        $input = new ArrayInput(array(
            'command' => $command,
            '--message-limit' => 1,
        ));
        $output = new BufferedOutput();
        $aplication->run($input, $output);

        return;
    }
}