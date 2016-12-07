<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Doctrine2\Application\Services;

use Doctrine\ORM\EntityManager;
use Hexagonal\Application\Services\TransactionalSession;
use Closure;

class DoctrineSession implements TransactionalSession
{
    /** @var EntityManager */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function executeAtomically(callable $operation)
    {
        return $this
            ->entityManager
            ->transactional(Closure::fromCallable($operation))
        ;
    }
}
