<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Accounts;

/**
 * Handle the persistence of members information
 */
interface Members
{
    /**
     * @param Identifier $id
     * @return Member | null
     */
    public function with(Identifier $id);

    /**
     * @param Member $member
     */
    public function add(Member $member);

    /**
     * @param Member $member
     */
    public function update(Member $member);
}
