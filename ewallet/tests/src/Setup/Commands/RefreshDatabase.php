<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Setup\Commands;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class RefreshDatabase extends Command
{
    protected function configure(): void
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
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var Application $application */
        $application = $this->getApplication();

        $command = $application->find('ewallet:db:drop');
        $command->run(new ArrayInput(['command' => 'ewallet:db:drop']), $output);

        $command = $application->find('ewallet:db:create');
        $command->run(new ArrayInput(['command' => 'ewallet:db:create']), $output);

        $command = $application->find('orm:schema-tool:update');
        $schemaInput = new ArrayInput([
            'command' => 'orm:schema-tool:update',
            '--force' => true,
        ]);
        $schemaInput->setInteractive(false);
        $command->run($schemaInput, $output);

        if ($input->getOption('seed') !== null) {
            $command = $application->find('ewallet:db:seed');
            $command->run(new ArrayInput(['command' => 'ewallet:db:seed']), $output);
        }

        return self::SUCCESS;
    }
}
