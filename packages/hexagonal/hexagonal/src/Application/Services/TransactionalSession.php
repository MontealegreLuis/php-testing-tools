<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Application\Services;

interface TransactionalSession
{
    /**
     * @return mixed
     */
    public function executeAtomically(callable $operation);
}
