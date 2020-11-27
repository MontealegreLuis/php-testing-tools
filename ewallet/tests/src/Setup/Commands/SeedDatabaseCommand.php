<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Setup\Commands;

use Alice\ThreeMembersWithSameBalanceFixture;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class SeedDatabaseCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('ewallet:db:seed')
            ->setDescription('Seed the database with some initial information');
    }

    /**
     * Seed some information to our database
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getHelper('em')->getEntityManager();
        $metadataFactory = $entityManager->getMetadataFactory();
        /** @var ClassMetadataInfo[] $metadata */
        $metadata = $metadataFactory->getAllMetadata();
        foreach ($metadata as $entityMetadata) {
            if (count($entityMetadata->getIdentifier()) > 0) {
                $this->truncateTable($entityMetadata);
            }
        }
        $fixture = new ThreeMembersWithSameBalanceFixture($entityManager);
        $fixture->load();
        $output->writeln('Database seed <info>completed successfully</info>!');

        return self::SUCCESS;
    }

    private function truncateTable(ClassMetadataInfo $metadata): void
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
