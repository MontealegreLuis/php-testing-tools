<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace DataBuilders\Ewallet\Memberships;

use DataBuilders\Random;
use Ewallet\Memberships\Email;
use Ewallet\Memberships\Member;
use Ewallet\Memberships\MemberId;
use function is_int;

use Money\Money;

final class MembersBuilder
{
    private ?string $id = null;

    private ?string $name = null;

    private ?string $email = null;

    /** @var int|Money|null */
    private $amount;

    public function named(string $name): MembersBuilder
    {
        $this->name = $name;

        return $this;
    }

    /** @param int|Money $amount */
    public function withBalance($amount): MembersBuilder
    {
        $this->amount = $amount;

        return $this;
    }

    public function withId(string $id): MembersBuilder
    {
        $this->id = $id;

        return $this;
    }

    public function withEmail(string $email): MembersBuilder
    {
        $this->email = $email;
        return $this;
    }

    public function build(): Member
    {
        $amount = Money::MXN(Random::cents());
        if ($this->amount !== null) {
            $amount = is_int($this->amount) ? Money::MXN($this->amount) : $this->amount;
        }

        return Member::withAccountBalance(
            new MemberId($this->id ?? Random::uuid()),
            $this->name ?? Random::name(),
            new Email($this->email ?? Random::email()),
            $amount
        );
    }
}
