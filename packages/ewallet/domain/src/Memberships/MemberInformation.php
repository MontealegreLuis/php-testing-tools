<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Memberships;

use Money\Money;

/**
 * This class enables access to a member's information
 */
class MemberInformation
{
    /** @var MemberId */
    private $memberId;

    /** @var string */
    private $name;

    /** @var Email */
    private $email;

    /** @var AccountInformation */
    private $account;

    public function __construct(
        MemberId $memberId,
        string $name,
        Email $email,
        AccountInformation $account
    ) {
        $this->memberId = $memberId;
        $this->name = $name;
        $this->email = $email;
        $this->account = $account;
    }

    public function accountBalance(): Money
    {
        return $this->account->balance();
    }

    public function id(): MemberId
    {
        return $this->memberId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): Email
    {
        return $this->email;
    }
}
