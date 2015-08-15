<?php
/**
 * PHP version 5.6
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
     * @param TransferFundsRequest $request
     * @return TransferFundsResponse
     */
    public function transfer(TransferFundsRequest $request)
    {
        $fromMember = $this->members->with($request->fromMemberId());
        $toMember = $this->members->with($request->toMemberId());

        $fromMember->transfer($request->amount(), $toMember);
        $this->publisher()->register($fromMember->events());

        $this->members->update($fromMember);
        $this->members->update($toMember);

        $this->notifier()->transferCompleted(
            new TransferFundsResponse($fromMember, $toMember)
        );
    }

    /**
     * @return TransferFundsNotifier
     */
    private function notifier()
    {
        if ($this->notifier) {
            return $this->notifier;
        }
        throw new LogicException('No notifier was attached');
    }
}
