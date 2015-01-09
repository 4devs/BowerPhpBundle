<?php

namespace FDevs\BowerPhpBundle\Command;

use Bowerphp\Bowerphp;
use Bowerphp\Output\BowerphpConsoleOutput;
use Bowerphp\Repository\GithubRepository;
use Guzzle\Log\ClosureLogAdapter;
use Guzzle\Log\MessageFormatter;
use Guzzle\Plugin\Log\LogPlugin;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends ContainerAwareCommand
{
    /**
     * @param OutputInterface $output
     *
     * @return Bowerphp
     */
    protected function getBowerphp(OutputInterface $output)
    {
        $container = $this->getContainer();
        $client = $container->get('f_devs_bower_php.github_client');

        $guzzle = $client->getHttpClient();
        if (OutputInterface::VERBOSITY_DEBUG <= $output->getVerbosity()) {
            $logger = function ($message) use ($output) {
                $finfo = new \finfo(FILEINFO_MIME);
                $msg = (substr($finfo->buffer($message), 0, 4) == 'text') ? $message : '(binary string)';
                $output->writeln('<info>Guzzle</info> '.$msg);
            };
            $logPlugin = new LogPlugin(new ClosureLogAdapter($logger), MessageFormatter::DEBUG_FORMAT);
            $guzzle->addSubscriber($logPlugin);
        }

        return new Bowerphp(
            $container->get('f_devs_bower_php.config'),
            $container->get('f_devs_bower_php.filesystem'),
            $client,
            new GithubRepository(),
            new BowerphpConsoleOutput($output)
        );
    }
}
