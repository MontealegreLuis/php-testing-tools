<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\SymfonyConsole\Commands;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;

abstract class DatabaseCommand extends Command
{
    /**
     * @param array $parameters
     * @return array
     */
    protected function withoutDatabaseName(array $parameters): array
    {
        $filtered = $parameters;
        unset($filtered['dbname'], $filtered['path']);

        return $filtered;
    }

    /**
     * @param array $parameters
     * @param Connection $connection
     * @return bool
     */
    protected function databaseExists(
        array $parameters,
        Connection $connection
    ): bool
    {
        if ($this->hasPath($parameters)) {
            return file_exists($this->databaseName($parameters));
        }

        return in_array(
            $this->databaseName($parameters),
            $connection->getSchemaManager()->listDatabases()
        );
    }

    /**
     * @param array $parameters
     * @return string
     */
    protected function databaseName(array $parameters): string
    {
        return $this->hasPath($parameters) ? $parameters['path'] : $parameters['dbname'];
    }

    /**
     * @param array $parameters
     * @return bool
     */
    protected function hasPath(array $parameters): bool
    {
        return isset($parameters['path']);
    }
}
