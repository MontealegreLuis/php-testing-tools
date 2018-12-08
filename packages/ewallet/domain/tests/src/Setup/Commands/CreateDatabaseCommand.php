<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Setup\Commands;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDatabaseCommand extends DatabaseCommand
{
    protected function configure()
    {
        $this
            ->setName('ewallet:db:create')
            ->setDescription('Create database')
        ;
    }

    /**
     * Create database unless it already exists.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $parameters = $this->getHelper('db')->getConnection()->getParams();
        try {
            $connection = DriverManager::getConnection($this->withoutDatabaseName($parameters));
            $this->createIfNotExists($output, $parameters, $connection);
        } catch (Exception $e) {
            $this->cannotCreateDatabase($output, $parameters, $e);
        } finally {
            $connection !== null && $connection->close();
        }
    }

    /** @throws \Doctrine\DBAL\DBALException */
    private function createIfNotExists(OutputInterface $output, array $parameters, Connection $connection): void
    {
        if ($this->databaseExists($parameters, $connection)) {
            $this->doNotCreateDatabase($output, $parameters);
        } else {
            $this->createDatabase($output, $connection, $parameters);
        }
    }

    /** @throws \Doctrine\DBAL\DBALException */
    private function createDatabase(OutputInterface $output, Connection $connection, array $parameters): void
    {
        $name = $this->databaseName($parameters);
        if (!$this->hasPath($parameters)) {
            $name = $connection->getDatabasePlatform()->quoteSingleIdentifier($name);
        }

        $connection->getSchemaManager()->createDatabase($name);

        $output->writeln(sprintf(
            '<info>Created database <comment>%s</comment></info>',
            $this->databaseName($parameters)
        ));
    }

    protected function doNotCreateDatabase(OutputInterface $output, array $parameters): void
    {
        $output->writeln(sprintf(
            '<info>Database <comment>%s</comment> already exists.</info>',
            $this->databaseName($parameters)
        ));
    }

    protected function cannotCreateDatabase(OutputInterface $output, array $parameters, Exception $exception): void
    {
        $output->writeln(sprintf(
            '<error>Could not create database <comment>%s</comment></error>',
            $this->databaseName($parameters)
        ));
        $output->writeln(sprintf('<error>%s</error>', $exception->getMessage()));
    }
}
