<?php

namespace Library\Bundle\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class AssetsIntegrateCommand  extends ContainerAwareCommand {

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('library:assets:integrate')
             ->setDescription('Integrate Twitter Bootstrap related files into the LibraryAppBundle.')
             ->addOption('symlink', null, InputOption::VALUE_NONE, 'Symlinks the assets instead of copying it')
             ->addOption('relative', null, InputOption::VALUE_NONE, 'Make relative symlinks')
             ->setHelp(<<<EOM
The <info>%command.name%</info> command installs Twitter Bootstrap and
jQuery assets into the public directory of the LibraryAppBundle.

<info>php %command.full_name% web</info>

To create a symlink to the asset files instead of copying them, use the
<info>--symlink</info> option:

<info>php %command.full_name% web --symlink</info>

To make symlink relative, add the <info>--relative</info> option:

<info>php %command.full_name% web --symlink --relative</info>

EOM
            )
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!function_exists('symlink') && $input->getOption('symlink')) {
            throw new \InvalidArgumentException('The symlink() function is not available on your system. You need to install the assets without the --symlink option.');
        }

        $assets = [
            'twbs/bootstrap' => [
                'target' => 'bootstrap',
                'filter' => function(\SplFileInfo $fileInfo) {
                    $path   = $fileInfo->getPath();
                    $result = preg_match('/bootstrap\/js$/', $path);
                    $result = ($result || preg_match('/bootstrap\/less$/', $path));
                    $result = ($result || preg_match('/bootstrap\/fonts$/', $path));
                    return $result;
                }
            ],
            'jquery/jquery'  => [ 'target' => 'jquery' ],
            'underscore/underscore'  => [ 'target' => 'underscore' ],
            'backbone/backbone'  => [ 'target' => 'backbone' ]
        ];

        $filesystem = $this->getContainer()->get('filesystem');
        $root       = $this->getContainer()->get('kernel')->getRootDir();
        $targetRoot = realpath($root . '/../src/Library/Bundle/AppBundle');

        if (!is_writeable($targetRoot)) {
            throw new \RuntimeException(sprintf('Unable to write in the "%s" directory.', $targetRoot));
        }

        foreach(array_keys($assets) as $source) {
            $check = $root . "/../vendor/" . $source;
            if (!$filesystem->exists($check)) {
                throw new \RuntimeException(sprintf('Dependency "%s" is not present in "%s".', $source, $check));
            }
        }

        $targetRoot .= '/Resources/public';
        $filesystem->mkdir($targetRoot, 0777);

        $output->writeln(sprintf('Installing assets as <comment>%s</comment>', $input->getOption('symlink') ? 'symlinks' : 'hard copies'));

        foreach($assets as $source => $destination) {
            $sourceDir = realpath($root . "/../vendor/" . $source);
            $targetDir = $targetRoot . '/' . $destination['target'];

            $output->writeln(sprintf('Installing assets for <comment>%s</comment> into <comment>%s</comment>', $source, $targetDir));

            $filesystem->remove($targetDir);

            if ($input->getOption('symlink')) {
                if ($input->getOption('relative')) {
                    $relativeOriginDir = $filesystem->makePathRelative($sourceDir, $targetRoot);
                } else {
                    $relativeOriginDir = $sourceDir;
                }
                $filesystem->symlink($relativeOriginDir, $targetDir);
            } else {
                $filesystem->mkdir($targetDir, 0777);
                $finder = Finder::create()->ignoreDotFiles(true)->in($sourceDir);

                if (isset($destination['filter'])) {
                    $finder->filter($destination['filter']);
                }

                $filesystem->mirror($sourceDir, $targetDir, $finder);
            }
        }

    }

}
