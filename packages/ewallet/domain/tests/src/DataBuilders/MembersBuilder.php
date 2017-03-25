<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\DataBuilders;

use Ewallet\Memberships\{Email, MemberId, Member};
use Faker\Factory;
use Money\Money;

class MembersBuilder
{
    /** @var Factory */
    private $faker;

    /** @var string */
    private $name;

    /** @var string */
    private $email;

    /** @var integer */
    private $amount;

    /** @var string */
    private $id;

    /**
     * Initialize member's information with fake data
     */
    public function __construct()
    {
        $this->faker = Factory::create();
        $this->reset();
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

    /**
     * @param integer|Money $amount
     */
    public function withBalance($amount): MembersBuilder
    {
        if (is_int($amount)) {
            $amount = Money::MXN($amount);
        }

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
        $member = Member::withAccountBalance(
            MemberId::withIdentity($this->id),
            $this->name,
            new Email($this->email),
            $this->amount
        );
        $this->reset();

        return $member;
    }

    private function reset(): void
    {
        $this->name = $this->faker->name;
        $this->email = $this->faker->email;
        $this->amount = Money::MXN($this->faker->numberBetween(0, 10000));
        $this->id = $this->faker->uuid;
    }
}
