<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Doctrine\Application\Messaging;

use Application\Messaging\MessageTracker;
use Application\Messaging\PublishedMessage;
use ContractTests\Application\Messaging\MessageTrackerTest;
use Doctrine\WithDatabaseSetup;
use SplFileInfo;

class MessageTrackerRepositoryTest extends MessageTrackerTest
{
    use WithDatabaseSetup;

    public function messageTracker(): MessageTracker
    {
        $this->cleanUpMessages();

        return new MessageTrackerRepository($this->setup->entityManager());
    }

    private function cleanUpMessages(): void
    {
        $this->_setupDatabaseSchema(new SplFileInfo(__DIR__ . '/../../../../../../'));
        $this->_executeDqlQuery('DELETE FROM ' . PublishedMessage::class);
    }
}
