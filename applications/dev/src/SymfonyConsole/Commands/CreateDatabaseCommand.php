<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\SymfonyConsole\Commands;

use Doctrine\DBAL\DriverManager;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDatabaseCommand extends Command
{
    /** @var array */
    private $options;

    /**
     * @inheritDoc
     */
    public function __construct(array $options)
    {
        parent::__construct();
        $this->options = $options;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('ewallet:db:create')
            ->setDescription('Create database')
        ;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $options = $this->options['doctrine']['connection'];
        $hasPath = isset($options['path']);
        $name = $hasPath ? $options['path']: $options['dbname'];
        unset($options['dbname'], $options['path']);
        $connection = DriverManager::getConnection($options);
        $databaseExists = in_array(
            $name,
            $connection->getSchemaManager()->listDatabases()
        );
        if (!$hasPath) {
            $name = $connection->getDatabasePlatform()->quoteSingleIdentifier($name);
        }
        try {
            if ($databaseExists) {
                $output->writeln(sprintf(
                    '<info>Database <comment>%s</comment> already exists.</info>',
                    $name
                ));
            } else {
                $connection->getSchemaManager()->createDatabase($name);
                $output->writeln(sprintf(
                    '<info>Created database <comment>%s</comment></info>',
                    $name
                ));
            }
        } catch (Exception $e) {
            $output->writeln(sprintf(
                '<error>Could not create database <comment>%s</comment></error>',
                $name
            ));
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }
        $connection->close();
    }
}
