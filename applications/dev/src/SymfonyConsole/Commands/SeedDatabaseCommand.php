<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\SymfonyConsole\Commands;

use Doctrine\ORM\EntityManagerInterface;
use Ewallet\Accounts\Member;
use Ewallet\Alice\ThreeMembersWithSameBalanceFixture;
use Hexagonal\{DomainEvents\StoredEvent, Messaging\PublishedMessage};
use Symfony\Component\Console\{
    Command\Command,
    Input\InputInterface,
    Output\OutputInterface
};

class SeedDatabaseCommand extends Command
{
    /** @var array */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    /**
     * Configures the current command.
     */
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
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null|int null or 0 if everything went fine, or an error code
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
     * @param string $entity
     */
    private function truncateTable(string $entity)
    {
        $metadata = $this->entityManager->getClassMetadata($entity);
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->query('SET FOREIGN_KEY_CHECKS=0');
        $truncate = $platform->getTruncateTableSql($metadata->getTableName());
        $connection->executeUpdate($truncate);
        $connection->query('SET FOREIGN_KEY_CHECKS=1');
    }
}
