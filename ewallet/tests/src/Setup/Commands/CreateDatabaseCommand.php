<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Setup\Commands;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;
use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateDatabaseCommand extends DatabaseCommand
{
    protected function configure(): void
    {
        $this
            ->setName('ewallet:db:create')
            ->setDescription('Create database');
    }

    /**
     * Create database unless it already exists.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $parameters = $this->getHelper('db')->getConnection()->getParams();
        try {
            $connection = DriverManager::getConnection($this->withoutDatabaseName($parameters));
            $this->createIfNotExists($output, $parameters, $connection);
        } catch (Exception $e) {
            $this->cannotCreateDatabase($output, $parameters, $e);
        } finally {
            if (isset($connection)) {
                $connection->close();
            }
        }
        return self::SUCCESS;
    }

    /**
     * @throws DBALException
     * @param mixed[] $parameters
     */
    private function createIfNotExists(OutputInterface $output, array $parameters, Connection $connection): void
    {
        if ($this->databaseExists($parameters, $connection)) {
            $this->doNotCreateDatabase($output, $parameters);
        } else {
            $this->createDatabase($output, $connection, $parameters);
        }
    }

    /**
     * @throws DBALException
     * @param mixed[] $parameters
     */
    private function createDatabase(OutputInterface $output, Connection $connection, array $parameters): void
    {
        $name = $this->databaseName($parameters);
        if (! $this->hasPath($parameters)) {
            $name = $connection->getDatabasePlatform()->quoteSingleIdentifier($name);
        }

        $connection->getSchemaManager()->createDatabase($name);

        $output->writeln(sprintf(
            '<info>Created database <comment>%s</comment></info>',
            $this->databaseName($parameters)
        ));
    }

    /** @param mixed[] $parameters */
    protected function doNotCreateDatabase(OutputInterface $output, array $parameters): void
    {
        $output->writeln(sprintf(
            '<info>Database <comment>%s</comment> already exists.</info>',
            $this->databaseName($parameters)
        ));
    }

    /** @param mixed[] $parameters */
    protected function cannotCreateDatabase(OutputInterface $output, array $parameters, Exception $exception): void
    {
        $output->writeln(sprintf(
            '<error>Could not create database <comment>%s</comment></error>',
            $this->databaseName($parameters)
        ));
        $output->writeln(sprintf('<error>%s</error>', $exception->getMessage()));
    }
}
