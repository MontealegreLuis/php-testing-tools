<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Doctrine\Application\Messaging;

use Application\ContractTests\Messaging\MessageTrackerTest;
use Application\Messaging\MessageTracker;
use Application\Messaging\PublishedMessage;
use Doctrine\DataStorageSetup;

class MessageTrackerRepositoryTest extends MessageTrackerTest
{
    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     */
    public function messageTracker(): MessageTracker
    {
        $this->cleanUpMessages();

        return new MessageTrackerRepository($this->setup->entityManager());
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     */
    private function cleanUpMessages(): void
    {
        $options = require __DIR__ . '/../../../../../../config/config.php';

        $this->setup = new DataStorageSetup($options);
        $this->setup->updateSchema();
        $this->setup->entityManager()->createQuery('DELETE FROM ' . PublishedMessage::class)->execute();
    }

    /** @var DataStorageSetup */
    private $setup;
}
