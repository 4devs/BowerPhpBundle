<?php

namespace FDevs\BowerPhpBundle\Command;

use Bowerphp\Installer\Installer;
use Bowerphp\Output\BowerphpConsoleOutput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class ListCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('fdevs:bower-php:list')
            ->setDescription('Lists installed packages')
            ->setHelp(<<<EOT
The <info>%command.name%</info> lists installed packages.

  <info>%command.full_name%</info>
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $installer = $this->getContainer()->get('f_devs_bower_php.installer');
        $bowerphp = $this->getBowerphp($output);
        $packages = $bowerphp->getInstalledPackages($installer, new Finder());

        $consoleOutput = new BowerphpConsoleOutput($output);

        foreach ($packages as $package) {
            $consoleOutput->writelnListPackage($package, $bowerphp);
        }
    }
}
