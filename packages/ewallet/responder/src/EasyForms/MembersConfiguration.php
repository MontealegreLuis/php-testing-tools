<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\EasyForms;

use Ewallet\Memberships\{MemberId, MembersRepository};

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
     * @param MemberId $memberId
     * @return \EWallet\Memberships\MemberInformation[]
     */
    public function getMembersChoicesExcluding(MemberId $memberId): array
    {
        $members = $this->members->excluding($memberId);
        $options = [];

        /** @var \EWallet\Memberships\Member $member */
        foreach ($members as $member) {
            $information = $member->information();
            $options[(string)$information->id()] = $information;
        }

        return $options;
    }
}
