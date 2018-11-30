<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\JmsSerializer;

use DateTime;
use Ewallet\Memberships\MemberId;
use Fakes\DomainEvents\InstantaneousEvent;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Ports\JmsSerializer\Application\DomainEvents\JsonSerializer;

class JsonSerializerTest extends TestCase
{
    /** @test */
    function it_serializes_a_domain_event_to_json()
    {
        $memberId = 'abc';
        $amountInCents = 10000;
        $occurredOnDate = '2015-10-24 12:39:51';
        $anEvent = new InstantaneousEvent(
            MemberId::withIdentity($memberId),
            Money::MXN($amountInCents),
            new DateTime($occurredOnDate)
        );

        $json = (new JsonSerializer())->serialize($anEvent);

        $this->assertEquals(
            "{\"occurred_on\":\"$occurredOnDate\",\"member_id\":\"$memberId\",\"amount\":\"$amountInCents\"}",
            $json,
            'JSON format for serialized event is incorrect'
        );
    }
}
