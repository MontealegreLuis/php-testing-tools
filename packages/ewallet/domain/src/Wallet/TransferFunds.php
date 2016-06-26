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

    /** @var TransferFundsNotifier */
    private $notifier;

    /**
     * @param Members $members
     */
    public function __construct(Members $members)
    {
        $this->members = $members;
    }

    /**
     * @param TransferFundsNotifier $notifier
     */
    public function attach(TransferFundsNotifier $notifier)
    {
        $this->notifier = $notifier;
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

        $this->notifier()->transferCompleted(
            new TransferFundsSummary($fromMember, $toMember)
        );
    }

    /**
     * @return TransferFundsNotifier
     * @throws LogicException
     */
    private function notifier(): TransferFundsNotifier
    {
        if ($this->notifier) {
            return $this->notifier;
        }
        throw new LogicException('No notifier was attached');
    }
}
