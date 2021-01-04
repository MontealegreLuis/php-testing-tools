<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace ContractTests\Ewallet\Memberships;

use DataBuilders\A;
use Ewallet\Memberships\MemberId;
use Ewallet\Memberships\Members;
use Ewallet\Memberships\UnknownMember;
use Money\Money;
use PHPUnit\Constraints\ProvidesMoneyConstraints;
use PHPUnit\Framework\TestCase;

abstract class MembersTest extends TestCase
{
    use ProvidesMoneyConstraints;

    abstract protected function membersInstance(): Members;

    /** @test */
    function it_finds_a_registered_member()
    {
        $this->members = $this->membersInstance();
        $registeredMember = A::member()->build();
        $this->members->save($registeredMember);
        $registeredMemberId = $registeredMember->id();

        $member = $this->members->with($registeredMemberId);

        $this->assertTrue(
            $member->equals($registeredMember),
            "Registered member with ID '{$registeredMemberId}' should be found"
        );
    }

    /** @test */
    function it_fails_to_find_an_unknown_member()
    {
        $this->members = $this->membersInstance();

        $this->expectException(UnknownMember::class);
        $this->members->with(new MemberId('unknown member id'));
    }

    /** @test */
    function it_updates_the_information_of_a_registered_member()
    {
        $this->members = $this->membersInstance();
        $fivePesos = 500;
        $recipient = A::member()->build();
        $sender = A::member()->withBalance(1000)->build();
        $this->members->save($sender);
        $sender->transfer(Money::MXN($fivePesos), $recipient);
        $this->members->save($sender);

        $this->assertBalanceAmounts(
            $fivePesos,
            $this->members->with($sender->id()),
            "Current member balance should be $fivePesos"
        );
    }

    /** @var Members */
    protected $members;

    /** @var \Ewallet\Memberships\Member */
    protected $registeredMember;

    /** @var \Ewallet\Memberships\Member A member with 1000 cents */
    private $sender;
}
