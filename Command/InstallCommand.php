<?php

namespace FDevs\BowerPhpBundle\Command;

use Bowerphp\Installer\Installer;
use Bowerphp\Package\Package;
use Bowerphp\Repository\GithubRepository;
use Bowerphp\Util\PackageNameVersionExtractor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Inspired by Composer https://github.com/composer/composer
 */
class InstallCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('fdevs:bower-php:install')
            ->setDescription('Installs the project dependencies from the bower.json file or a single specified package')
            ->addOption('save', 'S', InputOption::VALUE_NONE, 'Add installed package to bower.json file.')
            ->addArgument('package', InputArgument::OPTIONAL, 'Choose a package.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command reads the bower.json file from
the current directory, processes it, and downloads and installs all the
libraries and dependencies outlined in that file.

  <info>php %command.full_name%</info>

If an optional package name is passed, that package is installed.

  <info>php %command.full_name% packageName[#version]</info>

If an optional flag <comment>-S</comment> is passed, installed package is added
to bower.json file (only if bower.json file already exists).

EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('f_devs_bower_php.config')->setSaveToBowerJsonFile($input->getOption('save'));

        $packageName = $input->getArgument('package');

        $bowerphp = $this->getBowerphp($output);

        try {
            $installer = $this->getContainer()->get('f_devs_bower_php.installer');

            if (is_null($packageName)) {
                $bowerphp->installDependencies($installer);
            } else {
                $packageNameVersion = PackageNameVersionExtractor::fromString($packageName);
                $package = new Package($packageNameVersion->name, $packageNameVersion->version);
                $bowerphp->installPackage($package, $installer);
            }
        } catch (\RuntimeException $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            if ($e->getCode() == GithubRepository::VERSION_NOT_FOUND && !empty($package)) {
                $output->writeln(sprintf('Available versions: %s', implode(', ', $bowerphp->getPackageInfo($package, 'versions'))));
            }

            return $e->getCode();
        }
    }
}
