<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\Services;

use Closure;

trait ProvidesTransactionalOperations
{
    private TransactionalSession $session;

    public function setTransactionalSession(TransactionalSession $session): void
    {
        $this->session = $session;
    }

    public function execute(callable $operation): void
    {
        $this->session->executeAtomically(Closure::fromCallable($operation));
    }
}
