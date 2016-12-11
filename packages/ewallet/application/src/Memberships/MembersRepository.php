<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Memberships;

interface MembersRepository extends Members
{
    /**
     * This method is used to prevent the sender to transfer funds to itself.
     *
     * @param MemberId $senderId
     * @return Member[]
     */
    public function excluding(MemberId $senderId): array;
}
