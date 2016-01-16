<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\DataBuilders;

use Ewallet\Accounts\Email;
use Ewallet\Accounts\Identifier;
use Ewallet\Accounts\Member;
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
     * @param $name
     * @return MembersBuilder
     */
    public function withName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $email
     * @return MembersBuilder
     */
    public function withEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param integer|Money $amount
     * @return MembersBuilder
     */
    public function withBalance($amount)
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
    public function withId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Member
     */
    public function build()
    {
        $member = Member::withAccountBalance(
            Identifier::with($this->id),
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
