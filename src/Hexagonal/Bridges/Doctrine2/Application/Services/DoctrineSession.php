<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Bridges\Doctrine2\Application\Services;

use Doctrine\ORM\EntityManager;
use Hexagonal\Application\Services\TransactionalSession;

class DoctrineSession implements TransactionalSession
{
    /** @var EntityManager */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param callable $operation
     * @return mixed
     * @throws \Exception
     */
    public function executeAtomically(callable $operation)
    {
        return $this->entityManager->transactional($operation);
    }
}
