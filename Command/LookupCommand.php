<?php

namespace FDevs\BowerPhpBundle\Command;

use Bowerphp\Util\PackageNameVersionExtractor;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Lookup
 */
class LookupCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('fdevs:bower-php:lookup')
            ->setDescription('Look up a package URL by name')
            ->addArgument('package', InputArgument::REQUIRED, 'Choose a package.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command is used for search with exact match the repository URL package

  <info>php %command.full_name% packageName</info>
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $name = $input->getArgument('package');

        $packageNameVersion = PackageNameVersionExtractor::fromString($name);

        $bowerphp = $this->getBowerphp($output);
        $consoleOutput = new BowerphpConsoleOutput($output);

        $package = $bowerphp->lookupPackage($packageNameVersion->name);

        $consoleOutput->writelnSearchOrLookup($package['name'], $package['url']);
    }
}
