<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Accounts;

class MemberInformation
{
    /** @var Identifier */
    private $memberId;

    /** @var string */
    private $name;

    /** @var Email */
    private $email;

    /** @var Account */
    private $account;

    /**
     * @param Identifier $memberId
     * @param string $name
     * @param Email $email
     * @param Account $account
     */
    public function __construct(
        Identifier $memberId,
        $name,
        Email $email,
        Account $account
    ) {
        $this->memberId = $memberId;
        $this->name = $name;
        $this->email = $email;
        $this->account = $account;
    }


    /**
     * @return Money
     */
    public function accountBalance()
    {
        return $this->account->balance();
    }

    /**
     * @return Identifier
     */
    public function id()
    {
        return $this->memberId;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return Email
     */
    public function email()
    {
        return $this->email;
    }
}
