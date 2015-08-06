<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet;

use Ewallet\Accounts\Members;

class TransferFunds
{
    /** @var Members */
    private $members;

    /**
     * @param Members $members
     */
    public function __construct(Members $members)
    {
        $this->members = $members;
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

        $this->members->update($fromMember);
        $this->members->update($toMember);

        return new TransferFundsResponse($fromMember, $toMember);
    }
}
