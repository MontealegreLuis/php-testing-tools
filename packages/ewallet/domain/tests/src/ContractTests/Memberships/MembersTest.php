<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ContractTests\Memberships;

use Ewallet\Memberships\{MemberId, Members};
use Ewallet\DataBuilders\A;
use Ewallet\PHPUnit\Constraints\ProvidesMoneyConstraints;
use Money\Money;
use PHPUnit_Framework_TestCase as TestCase;

abstract class MembersTest extends TestCase
{
    use ProvidesMoneyConstraints;

    /** @var Members */
    protected $members;

    /** @var \Ewallet\Memberships\Member */
    protected $registeredMember;

    /** @var \Ewallet\Memberships\Member A member with 1000 cents */
    private $sender;

    abstract protected function membersInstance(): Members;

    /** @before */
    function generateFixtures(): void
    {
        $this->members = $this->membersInstance();
        $this->registeredMember = A::member()->build();
        $this->sender = A::member()->withBalance(1000)->build();

        $this->members->add($this->sender);
        $this->members->add($this->registeredMember);
        $this->members->add(A::member()->build());
    }

    /** @test */
    function it_finds_a_registered_member()
    {
        $member = $this->members->with($this->registeredMember->information()->id());

        $this->assertTrue(
            $member->equals($this->registeredMember),
            'Registered member with ID "abcd" should be found'
        );
    }

    /**
     * @test
     * @expectedException \Ewallet\Memberships\UnknownMember
     */
    function it_does_not_find_a_non_existing_member()
    {
        $this->members->with(MemberId::withIdentity('unknown member id'));
    }

    /** @test */
    function it_updates_the_information_of_a_registered_member()
    {
        $sender = $this->members->with($this->sender->information()->id());
        $sender->transfer(Money::MXN(500), A::member()->build());

        $this->members->update($sender);

        $this->assertBalanceAmounts(
            500,
            $this->members->with($sender->information()->id()),
            "Current member balance should be 500"
        );
    }
}
