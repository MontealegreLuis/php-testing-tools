<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\Services;

/**
 * Used by actions that need to execute operations atomically
 */
interface TransactionalSession
{
    public function executeAtomically(callable $operation): void;
}
