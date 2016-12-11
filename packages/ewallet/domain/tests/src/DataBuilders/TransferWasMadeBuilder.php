<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\DataBuilders;

use Ewallet\Memberships\{MemberId, TransferWasMade};
use Faker\Factory;
use Money\Money;

class TransferWasMadeBuilder
{
    /** @var Factory */
    private $factory;

    /** @var string */
    private $senderId;

    /** @var integer */
    private $amount;

    /** @var integer */
    private $recipientId;

    public function __construct()
    {
        $this->factory = Factory::create();
        $this->reset();
    }

    public function build(): TransferWasMade
    {
        $event = new TransferWasMade(
            MemberId::withIdentity($this->senderId),
            Money::MXN($this->amount),
            MemberId::withIdentity($this->recipientId)
        );

        $this->reset();

        return $event;
    }

    /**
     * Set random initial values for the event
     */
    private function reset()
    {
        $this->senderId = $this->factory->uuid;
        $this->amount = $this->factory->numberBetween(1, 10000);
        $this->recipientId = $this->factory->uuid;
    }
}
