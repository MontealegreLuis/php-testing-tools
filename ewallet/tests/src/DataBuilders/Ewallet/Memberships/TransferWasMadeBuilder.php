<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace DataBuilders\Ewallet\Memberships;

use Ewallet\Memberships\MemberId;
use Ewallet\Memberships\TransferWasMade;
use Faker\Factory;
use Faker\Generator;
use Money\Money;

class TransferWasMadeBuilder
{
    private Generator $factory;

    private string $senderId;

    private int $amount;

    private string $recipientId;

    public function __construct()
    {
        $this->factory = Factory::create();
        $this->reset();
    }

    public function build(): TransferWasMade
    {
        $event = new TransferWasMade(
            new MemberId($this->senderId),
            Money::MXN($this->amount),
            new MemberId($this->recipientId)
        );

        $this->reset();

        return $event;
    }

    /**
     * Set random initial values for the event
     */
    private function reset(): void
    {
        $this->senderId = $this->factory->uuid;
        $this->amount = $this->factory->numberBetween(1, 10_000);
        $this->recipientId = $this->factory->uuid;
    }
}
