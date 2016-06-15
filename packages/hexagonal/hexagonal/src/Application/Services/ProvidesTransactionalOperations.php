<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Application\Services;

trait ProvidesTransactionalOperations
{
    /** @var TransactionalSession */
    private $session;

    /**
     * @param TransactionalSession $session
     */
    public function setTransactionalSession(TransactionalSession $session)
    {
        $this->session = $session;
    }
}
