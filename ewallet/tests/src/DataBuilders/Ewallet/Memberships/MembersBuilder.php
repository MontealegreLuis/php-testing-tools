<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace DataBuilders\Ewallet\Memberships;

use Ewallet\Memberships\Email;
use Ewallet\Memberships\Member;
use Ewallet\Memberships\MemberId;
use Faker\Factory;
use Faker\Generator;
use Money\Money;

class MembersBuilder
{
    private Generator $faker;

    private string $name;

    private string $email;

    /** @var integer */
    private $amount;

    private string $id;

    /**
     * Initialize member's information with fake data
     */
    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function named(string $name): MembersBuilder
    {
        $this->name = $name;

        return $this;
    }

    public function withEmail(string $email): MembersBuilder
    {
        $this->email = $email;

        return $this;
    }

    /** @param integer|Money $amount */
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

    public function build(): Member
    {
        $amount = Money::MXN($this->faker->numberBetween(1, 10_000));
        if ($this->amount) {
            $amount = \is_int($this->amount) ? Money::MXN($this->amount) : $this->amount;
        }

        return Member::withAccountBalance(
            new MemberId($this->id ?? $this->faker->uuid),
            $this->name ?? $this->faker->name,
            new Email($this->email ?? $this->faker->email),
            $amount
        );
    }
}
