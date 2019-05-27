<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Setup\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RefreshDatabase extends Command
{
    protected function configure()
    {
        $this
            ->setName('ewallet:db:refresh')
            ->setDescription('Recreates and optionally seeds the database')
            ->setHelp(
                <<<HELP
Refresh the database

<info>bin/setup ewallet:db:refresh</info>

It also seeds the database if the option <info>seed</info> is passed:

<info>bin/setup ewallet:db:refresh -s</info>
HELP
            )
            ->addOption(
                'seed',
                's',
                InputOption::VALUE_NONE,
                'Seed the database with fake information'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find('ewallet:db:drop');
        $command->run(new ArrayInput(['command' => 'ewallet:db:drop']), $output);

        $command = $this->getApplication()->find('ewallet:db:create');
        $command->run(new ArrayInput(['command' => 'ewallet:db:create']), $output);

        $command = $this->getApplication()->find('orm:schema-tool:update');
        $schemaInput = new ArrayInput([
            'command' => 'orm:schema-tool:update',
            '--force' => true,
        ]);
        $schemaInput->setInteractive(false);
        $command->run($schemaInput, $output);

        if ($input->getOption('seed')) {
            $command = $this->getApplication()->find('ewallet:db:seed');
            $command->run(new ArrayInput(['command' => 'ewallet:db:seed']), $output);
        }
    }
}
