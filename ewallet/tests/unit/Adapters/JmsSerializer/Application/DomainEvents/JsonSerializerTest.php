<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\JmsSerializer\Application\DomainEvents;

use Carbon\CarbonImmutable;
use Ewallet\Memberships\MemberId;
use Ewallet\Memberships\TransferWasMade;
use Fakes\Application\FakeClock;
use Money\Money;
use PHPUnit\Framework\TestCase;

class JsonSerializerTest extends TestCase
{
    /** @test */
    function it_serializes_a_domain_event_to_json()
    {
        $clock = new FakeClock(CarbonImmutable::parse('2015-10-24 12:39:51'));

        $json = (new JsonSerializer())->serialize(new TransferWasMade(
            new MemberId('abc'),
            Money::MXN(10000),
            new MemberId('xyz'),
            $clock
        ));

        $this->assertEquals(
            '{"occurred_on":"2015-10-24 12:39:51","sender_id":"abc","amount":"10000","recipient_id":"xyz"}',
            $json,
            'JSON format for serialized event is incorrect'
        );
    }
}
