<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Application\Services;

use Closure;

trait ProvidesTransactionalOperations
{
    /** @var TransactionalSession */
    private $session;

    public function setTransactionalSession(TransactionalSession $session)
    {
        $this->session = $session;
    }

    public function execute(callable $operation): void
    {
        $this->session->executeAtomically(Closure::fromCallable($operation));
    }
}
