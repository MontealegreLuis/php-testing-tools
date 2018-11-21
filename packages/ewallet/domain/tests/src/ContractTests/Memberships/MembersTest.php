<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ContractTests\Memberships;

use Ewallet\Memberships\{MemberId, Members, UnknownMember};
use Ewallet\DataBuilders\A;
use Ewallet\PHPUnit\Constraints\ProvidesMoneyConstraints;
use Money\Money;
use PHPUnit\Framework\TestCase;

abstract class MembersTest extends TestCase
{
    use ProvidesMoneyConstraints;

    abstract protected function membersInstance(): Members;

    /** @test */
    function it_finds_a_registered_member()
    {
        $registeredMemberId = $this->registeredMember->id();

        $member = $this->members->with($registeredMemberId);

        $this->assertTrue(
            $member->equals($this->registeredMember),
            'Registered member with ID "abcd" should be found'
        );
    }

    /** @test */
    function it_does_not_find_a_non_existing_member()
    {
        $this->expectException(UnknownMember::class);

        $this->members->with(MemberId::withIdentity('unknown member id'));
    }

    /** @test */
    function it_updates_the_information_of_a_registered_member()
    {
        $fivePesos = 500;
        $recipient = A::member()->build();
        $sender = $this->members->with($this->sender->id());

        $sender->transfer(Money::MXN($fivePesos), $recipient);
        $this->members->update($sender);

        $this->assertBalanceAmounts(
            $fivePesos,
            $this->members->with($sender->id()),
            "Current member balance should be $fivePesos"
        );
    }

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

    /** @var Members */
    protected $members;

    /** @var \Ewallet\Memberships\Member */
    protected $registeredMember;

    /** @var \Ewallet\Memberships\Member A member with 1000 cents */
    private $sender;
}
