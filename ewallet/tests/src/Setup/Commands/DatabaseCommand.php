<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Setup\Commands;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;

abstract class DatabaseCommand extends Command
{
    protected function withoutDatabaseName(array $parameters): array
    {
        $filtered = $parameters;
        unset($filtered['dbname'], $filtered['path']);

        return $filtered;
    }

    protected function databaseExists(array $parameters, Connection $connection): bool
    {
        if ($this->hasPath($parameters)) {
            return file_exists($this->databaseName($parameters));
        }

        return \in_array($this->databaseName($parameters), $connection->getSchemaManager()->listDatabases(), true);
    }

    protected function databaseName(array $parameters): string
    {
        return $this->hasPath($parameters) ? $parameters['path'] : $parameters['dbname'];
    }

    protected function hasPath(array $parameters): bool
    {
        return isset($parameters['path']);
    }
}
