<?php

namespace Spatie\Php7to5\Console;

use Spatie\Php7to5\DirectoryConverter;
use Spatie\Php7to5\Exceptions\InvalidArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConvertCommand extends Command
{
    protected function configure()
    {
        $this->setName('convert')
            ->setDescription('Convert PHP 7 code to PHP 5 code')
            ->addArgument(
                'source',
                InputArgument::REQUIRED,
                'A PHP 7 file or a directory containing PHP 7 files'
            )
            ->addArgument(
                'destination',
                InputArgument::REQUIRED,
                'The file or path where the PHP 5 code should be saved'
            )
            ->addOption(
                'alsoCopyNonPhpFiles',
                'nonPhp',
                InputOption::VALUE_REQUIRED,
                'Should non php files be copied over as well',
                true
            );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->guardAgainstInvalidArguments($input);

        $source = $input->getArgument('source');
        $destination = $input->getArgument('destination');

        $output->writeln("Converting {$source} to {$destination}...");
        $output->writeln('');

        $converter = new DirectoryConverter($source);

        if (!$input->getOption('alsoCopyNonPhpFiles')) {
            $converter->doNotCopyNonPhpFiles();
        }

        $converter->savePhp5FilesTo($destination);

        $output->writeln('All Done!');
        $output->writeln('');

        return 0;
    }

    protected function guardAgainstInvalidArguments(InputInterface $input)
    {
        if (!is_dir($input->getArgument('source'))) {
            throw InvalidArgument::directoryDoesNotExist($input->getArgument('source'));
        }
    }
}
