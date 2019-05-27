<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ports\Doctrine\Application\Services;

use Application\Services\TransactionalSession;
use Closure;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineSession implements TransactionalSession
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function executeAtomically(callable $operation): void
    {
        $this->entityManager->transactional(Closure::fromCallable($operation));
    }
}
