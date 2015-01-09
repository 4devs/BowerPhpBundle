<?php

namespace FDevs\BowerPhpBundle\Command;

use Bowerphp\Output\BowerphpConsoleOutput;
use Bowerphp\Package\Package;
use Bowerphp\Util\PackageNameVersionExtractor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Info
 */
class InfoCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('fdevs:bower-php:info')
            ->setDescription('Displays overall information of a package or of a particular version')
            ->addArgument('package', InputArgument::REQUIRED, 'Choose a package.')
            ->addArgument('property', InputArgument::OPTIONAL, 'A property present in bower.json.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command displays overall information of a package or of a particular version.
If you pass a property present in bower.json, you can get the correspondent value.

  <info>php %command.full_name% package</info>
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $packageName = $input->getArgument('package');
        $property = $input->getArgument('property');

        $packageNameVersion = PackageNameVersionExtractor::fromString($packageName);

        $package = new Package($packageNameVersion->name, $packageNameVersion->version);
        $bowerphp = $this->getBowerphp($output);

        $bowerJsonFile = $bowerphp->getPackageBowerFile($package);
        if ($packageNameVersion->version == '*') {
            $versions = $bowerphp->getPackageInfo($package, 'versions');
        }
        $consoleOutput = new BowerphpConsoleOutput($output);
        if (!is_null($property)) {
            $bowerArray = json_decode($bowerJsonFile, true);
            $propertyValue = isset($bowerArray[$property]) ? $bowerArray[$property] : '';
            $consoleOutput->writelnJsonText($propertyValue);

            return;
        }
        $consoleOutput->writelnJson($bowerJsonFile);
        if ($packageNameVersion->version != '*') {
            return;
        }
        $output->writeln('');
        if (empty($versions)) {
            $output->writeln('No versions available.');
        } else {
            $output->writeln('<fg=cyan>Available versions:</fg=cyan>');
            foreach ($versions as $vrs) {
                $output->writeln("- $vrs");
            }
        }
    }
}
