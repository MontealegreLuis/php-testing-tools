<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\SymfonyConsole\Commands;

use Nelmio\Alice\Fixtures;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TestHelpers\Bridges\ProvidesDoctrineSetup;

class SeedDatabaseCommand extends Command
{
    use ProvidesDoctrineSetup;

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setName('ewallet:seed')
            ->setDescription('Seed the database with some initial information')
        ;
    }

    /**
     * Seed some information to our database
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null|int null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->_setUpDoctrine();
        Fixtures::load(
            __DIR__ . '/../../../../../tests/_data/fixtures/members.yml',
            $this->entityManager
        );
    }
}
