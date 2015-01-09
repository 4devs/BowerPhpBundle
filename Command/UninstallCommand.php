<?php

namespace FDevs\BowerPhpBundle\Command;

use Bowerphp\Installer\Installer;
use Bowerphp\Package\Package;
use Bowerphp\Util\PackageNameVersionExtractor;
use Bowerphp\Util\ZipArchive;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Uninstall
 */
class UninstallCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('fdevs:bower-php:uninstall')
            ->setDescription('Uninstalls a single specified package')
            ->addArgument('package', InputArgument::REQUIRED, 'Choose a package.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command uninstall a package.

  <info>php %command.full_name% packageName</info>
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $packageName = $input->getArgument('package');

        $bowerphp = $this->getBowerphp($output);

        try {
            $installer = $this->getContainer()->get('f_devs_bower_php.installer');

            $packageNameVersion = PackageNameVersionExtractor::fromString($packageName);

            $package = new Package($packageNameVersion->name, $packageNameVersion->version);
            $bowerphp->uninstallPackage($package, $installer);
        } catch (\RuntimeException $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));

            return $e->getCode();
        }

        $output->writeln('');
    }
}
