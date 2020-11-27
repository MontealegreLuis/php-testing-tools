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

final class DropDatabaseCommand extends DatabaseCommand
{
    protected function configure(): void
    {
        $this
            ->setName('ewallet:db:drop')
            ->setDescription('Drops the database');
    }

    /**
     * Drop database unless it does not exist.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $parameters = $this->getHelper('db')->getConnection()->getParams();
        try {
            $arr = $this->withoutDatabaseName($parameters);
            $connection = DriverManager::getConnection($arr);
            $this->dropIfExists($output, $parameters, $connection);
        } catch (Exception $e) {
            $this->cannotDropDatabase($output, $parameters, $e);
        } finally {
            if (isset($connection)) {
                $connection->close();
            }
        }
        return self::SUCCESS;
    }

    /** @param mixed[] $parameters */
    private function dropIfExists(OutputInterface $output, array $parameters, Connection $connection): void
    {
        if ($this->databaseExists($parameters, $connection)) {
            $this->dropDatabase($output, $connection, $parameters);
        } else {
            $this->doNotDropDatabase($output, $parameters);
        }
    }

    /**
     * @throws DBALException
     * @param mixed[] $parameters
     */
    private function dropDatabase(OutputInterface $output, Connection $connection, array $parameters): void
    {
        $name = $this->databaseName($parameters);
        if (! $this->hasPath($parameters)) {
            $name = $connection->getDatabasePlatform()->quoteSingleIdentifier($name);
        }

        $connection->getSchemaManager()->dropDatabase($name);

        $output->writeln(sprintf(
            '<info>Dropped database <comment>%s</comment></info>',
            $name
        ));
    }

    /** @param mixed[] $parameters */
    private function doNotDropDatabase(OutputInterface $output, array $parameters): void
    {
        $output->writeln(sprintf(
            '<info>Database <comment>%s</comment> doesn\'t exist. Skipped.</info>',
            $this->databaseName($parameters)
        ));
    }

    /** @param mixed[] $parameters */
    protected function cannotDropDatabase(OutputInterface $output, array $parameters, Exception $exception): void
    {
        $output->writeln(sprintf(
            '<error>Could not drop database ,<comment>%s</comment></error>',
            $this->databaseName($parameters)
        ));
        $output->writeln(sprintf('<error>%s</error>', $exception->getMessage()));
    }
}
