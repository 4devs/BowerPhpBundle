<?php

namespace FDevs\BowerPhpBundle\Command;

use Bowerphp\Output\BowerphpConsoleOutput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Search
 */
class SearchCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('fdevs:bower-php:search')
            ->setDescription('Search for a package by name')
            ->addArgument('name', InputArgument::REQUIRED, 'Name to search for.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command searches for a package by name.

  <info>php %command.full_name% name</info>
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        $bowerphp = $this->getBowerphp($output);
        $packages = $bowerphp->searchPackages($name);

        if (empty($packages)) {
            $output->writeln('No results.');
        } else {
            $output->writeln('Search results:'.PHP_EOL);
            $consoleOutput = new BowerphpConsoleOutput($output);
            array_walk($packages, function ($package) use ($consoleOutput) {
                $consoleOutput->writelnSearchOrLookup($package['name'], $package['url'], 4);
            });
        }
    }
}
