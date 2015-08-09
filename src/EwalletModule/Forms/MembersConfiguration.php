<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Forms;

use Ewallet\Accounts\Identifier;
use Ewallet\Bridges\Doctrine2\Accounts\MembersRepository;

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
            $information = $member->information();
            $options[(string) $information->id()] = $information;
        }

        return $options;
    }

    /**
     * @param string $fromMemberId
     * @return array
     */
    public function getMembersWhiteList($fromMemberId)
    {
        $memberId = Identifier::any();
        if (!is_null($fromMemberId)) {
            $memberId = Identifier::fromString($fromMemberId);
        }

        return array_keys(
            $this->getMembersChoicesExcluding($memberId)
        );
    }
}
