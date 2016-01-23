<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\EasyForms;

use Ewallet\Accounts\Identifier;
use Ewallet\Doctrine2\Accounts\MembersRepository;

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
     * @return \EWallet\Accounts\MemberInformation[]
     */
    public function getMembersChoicesExcluding(Identifier $memberId)
    {
        $members = $this->members->excluding($memberId);
        $options = [];

        /** @var \EWallet\Accounts\Member $member */
        foreach ($members as $member) {
            $information = $member->information();
            $options[(string)$information->id()] = $information;
        }

        return $options;
    }
}
