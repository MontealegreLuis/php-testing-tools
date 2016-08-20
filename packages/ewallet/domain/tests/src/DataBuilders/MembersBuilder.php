<?php
/**
 * PHP version 7.0
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

    /**
     * @param string $name
     * @return MembersBuilder
     */
    public function withName(string $name): MembersBuilder
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $email
     * @return MembersBuilder
     */
    public function withEmail(string $email): MembersBuilder
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param integer|Money $amount
     * @return MembersBuilder
     */
    public function withBalance($amount): MembersBuilder
    {
        if (is_int($amount)) {
            $amount = Money::MXN($amount);
        }

        $this->amount = $amount;

        return $this;
    }

    /**
     * @param string $id
     * @return MembersBuilder
     */
    public function withId(string $id): MembersBuilder
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Member
     */
    public function build(): Member
    {
        $member = Member::withAccountBalance(
            MemberId::with($this->id),
            $this->name,
            new Email($this->email),
            $this->amount
        );
        $this->reset();

        return $member;
    }

    protected function reset()
    {
        $this->name = $this->faker->name;
        $this->email = $this->faker->email;
        $this->amount = Money::MXN($this->faker->numberBetween(0, 10000));
        $this->id = $this->faker->uuid;
    }
}
