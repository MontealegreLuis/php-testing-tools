<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Doctrine\Application\Services;

use Application\Services\TransactionalSession;
use Closure;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineSession implements TransactionalSession
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function executeAtomically(callable $operation): object
    {
        return $this->entityManager->transactional(Closure::fromCallable($operation));
    }
}
