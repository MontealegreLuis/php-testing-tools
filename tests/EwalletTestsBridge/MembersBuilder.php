<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletTestsBridge;

use Ewallet\Accounts\Identifier;
use Ewallet\Accounts\Member;
use Faker\Factory;
use Money\Money;

class MembersBuilder
{
    /** @var string */
    private $name;

    /** @var integer */
    private $amount;

    /** @var string */
    private $id;

    /**
     * Initialize member's information with fake data
     */
    private function __construct()
    {
        $faker = Factory::create();
        $this->name = $faker->name;
        $this->amount = (integer) $faker->numberBetween(0, 10000);
        $this->id = $faker->uuid;
    }

    /**
     * @return MembersBuilder
     */
    public static function aMember()
    {
        return new self();
    }

    /**
     * @param integer $amount
     * @return MembersBuilder
     */
    public function withBalance($amount)
    {
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
        return Member::withAccountBalance(
            Identifier::fromString($this->id),
            $this->name,
            Money::MXN($this->amount)
        );
    }
}