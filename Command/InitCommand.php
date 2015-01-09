<?php

namespace FDevs\BowerPhpBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Init
 */
class InitCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('fdevs:bower-php:init')
            ->setDescription('Initializes a bower.json file')
            ->addOption('package-name', 'p', InputOption::VALUE_OPTIONAL, 'Package name')
            ->addOption('author', 'a', InputOption::VALUE_OPTIONAL, 'Author')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command initializes a bower.json file in
the app/config directory.

  <info>php %command.full_name%</info>
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getOption('package-name');
        $author = $input->getOption('author');

        // @codeCoverageIgnoreStart
        if ((!$name || !$author) && $input->isInteractive()) {
            /** @var \Symfony\Component\Console\Helper\DialogHelper $dialog */
            $dialog = $this->getHelperSet()->get('dialog');

            $name = $name ?: $dialog->ask($output, 'Please specify a name for project: ', get_current_user());
            $author = $author ?: $dialog->ask($output, 'Please specify an author: ', sprintf('%s <%s>', $this->getGitInfo('user.name'), $this->getGitInfo('user.email')));

        }
        // @codeCoverageIgnoreEnd
        $bowerphp = $this->getBowerphp($output);
        $bowerphp->init(['author' => $author, 'name' => $name]);

        $bowerFile = $this->getContainer()->get('f_devs_bower_php.config')->getBowerFile();

        $output->writeln(sprintf('file <info>%s</info> created, with package name <info>%s</info>, author <info>%s</info>', $bowerFile, $name, $author));
    }

    /**
     * Get some info from local git
     *
     * @param string $info info type
     *
     * @return string
     */
    private function getGitInfo($info = 'user.name')
    {
        $output = [];
        $return = 0;
        $info = exec("git config --get $info", $output, $return);

        if ($return === 0) {
            return $info;
        }
    }
}
