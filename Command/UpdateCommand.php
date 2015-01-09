<?php
namespace FDevs\BowerPhpBundle\Command;

use Bowerphp\Installer\Installer;
use Bowerphp\Package\Package;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Inspired by Composer https://github.com/composer/composer
 */
class UpdateCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('fdevs:bower-php:update')
            ->setDescription('Update the project dependencies from the bower.json file or a single specified package')
            ->addArgument('package', InputArgument::OPTIONAL, 'Choose a package.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command reads the bower.json file from
the current directory, processes it, and downloads and installs all the
libraries and dependencies outlined in that file.

  <info>php %command.full_name%</info>

If an optional package name is passed, only that package is updated.

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
        $installer = $this->getContainer()->get('f_devs_bower_php.installer');

        try {
            $bowerphp = $this->getBowerphp($output);
            if (is_null($packageName)) {
                $bowerphp->updatePackages($installer);
            } else {
                $bowerphp->updatePackage(new Package($packageName), $installer);
            }
        } catch (RuntimeException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}
