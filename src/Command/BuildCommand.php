<?php

namespace Laces\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'build',
    description: 'Builds and publishes the Laces starter kit'
)]
class BuildCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>[Laces]</info> Hello from the build command!');

        // Placeholder — logic will come next
        return Command::SUCCESS;
    }
}
