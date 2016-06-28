<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet;

use Ewallet\Accounts\Members;
use Hexagonal\DomainEvents\PublishesEvents;
use LogicException;

class TransferFunds
{
    use PublishesEvents;

    /** @var Members */
    private $members;

    /** @var CanTransferFunds */
    private $action;

    /**
     * @param Members $members
     */
    public function __construct(Members $members)
    {
        $this->members = $members;
    }

    /**
     * @param CanTransferFunds $action
     */
    public function attach(CanTransferFunds $action)
    {
        $this->action = $action;
    }

    /**
     * @param TransferFundsInformation $information
     */
    public function transfer(TransferFundsInformation $information)
    {
        $fromMember = $this->members->with($information->fromMemberId());
        $toMember = $this->members->with($information->toMemberId());

        $fromMember->transfer($information->amount(), $toMember);

        $this->members->update($fromMember);
        $this->members->update($toMember);

        $this->publisher()->publish($fromMember->events());

        $this->action()->transferCompleted(
            new TransferFundsSummary($fromMember, $toMember)
        );
    }

    /**
     * @return CanTransferFunds
     * @throws LogicException
     */
    private function action(): CanTransferFunds
    {
        if ($this->action) {
            return $this->action;
        }
        throw new LogicException('No action was attached');
    }
}
