<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\SymfonyConsole\Commands;

use Doctrine\ORM\EntityManagerInterface;
use Ewallet\Memberships\Member;
use Ewallet\Alice\ThreeMembersWithSameBalanceFixture;
use Hexagonal\{DomainEvents\StoredEvent, Messaging\PublishedMessage};
use Symfony\Component\Console\{
    Command\Command,
    Input\InputInterface,
    Output\OutputInterface
};

class SeedDatabaseCommand extends Command
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setName('ewallet:db:seed')
            ->setDescription('Seed the database with some initial information')
        ;
    }

    /**
     * Seed some information to our database
     *
     * @throws \Doctrine\DBAL\DBALException if any of the tables cannot be truncated
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->truncateTable(Member::class);
        $this->truncateTable(StoredEvent::class);
        $this->truncateTable(PublishedMessage::class);
        $fixture = new ThreeMembersWithSameBalanceFixture($this->entityManager);
        $fixture->load();
        $output->writeln('Database seed <info>completed successfully</info>!');
    }

    /**
     * @throws \Doctrine\DBAL\DBALException If any of the queries fail
     */
    private function truncateTable(string $entity): void
    {
        $metadata = $this->entityManager->getClassMetadata($entity);
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->query('SET FOREIGN_KEY_CHECKS=0');
        $truncate = $platform->getTruncateTableSQL($metadata->getTableName());
        $connection->executeUpdate($truncate);
        $connection->query('SET FOREIGN_KEY_CHECKS=1');
    }
}
