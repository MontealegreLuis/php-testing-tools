<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Setup\Commands;

use Alice\ThreeMembersWithSameBalanceFixture;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\DBAL\DBALException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SeedDatabaseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('ewallet:db:seed')
            ->setDescription('Seed the database with some initial information');
    }

    /**
     * Seed some information to our database
     *
     * @throws DBALException if any of the tables cannot be truncated
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityManager = $this->getHelper('em')->getEntityManager();
        $metadataFactory = $entityManager->getMetadataFactory();
        $metadata = $metadataFactory->getAllMetadata();
        /** @var ClassMetadata $entityMetadata */
        foreach ($metadata as $entityMetadata) {
            if (! empty($entityMetadata->getIdentifier())) {
                $this->truncateTable($entityMetadata);
            }
        }
        $fixture = new ThreeMembersWithSameBalanceFixture($entityManager);
        $fixture->load();
        $output->writeln('Database seed <info>completed successfully</info>!');
    }

    /**
     * @throws DBALException If any of the queries fail
     */
    private function truncateTable(ClassMetadata $metadata): void
    {
        $connection = $this->getHelper('db')->getConnection();
        $platform = $connection->getDatabasePlatform();
        $isMySQL = $platform->getName() === 'mysql';
        if ($isMySQL) {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
        }
        $connection->executeUpdate($platform->getTruncateTableSQL($metadata->getTableName()));
        if ($isMySQL) {
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}
