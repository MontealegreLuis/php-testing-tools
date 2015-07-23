<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Forms;

use Ewallet\Accounts\Identifier;
use EwalletDoctrineBridge\Accounts\MembersRepository;

class MembersConfiguration
{
    /** @var MembersRepository */
    private $members;

    /**
     * @param MembersRepository $members
     */
    public function __construct(MembersRepository $members)
    {
        $this->members = $members;
    }

    /**
     * @param Identifier $memberId
     * @return array
     */
    public function getMembersChoicesExcluding(Identifier $memberId)
    {
        $members = $this->members->excluding($memberId);
        $options = [];

        /** @var \EWallet\Accounts\Member $member */
        foreach ($members as $member) {
            $amount = number_format(
                round($member->accountBalance()->getAmount() / 100, 2), 2
            );
            $label = "{$member->name()} \${$amount} MXN";
            $options[(string) $member->id()] = $label;
        }

        return $options;
    }
}
