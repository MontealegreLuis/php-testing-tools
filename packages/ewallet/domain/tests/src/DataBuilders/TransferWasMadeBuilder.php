<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\DataBuilders;

use Ewallet\Accounts\{MemberId, TransferWasMade};
use Faker\Factory;
use Money\Money;

class TransferWasMadeBuilder
{
    /** @var Factory */
    private $factory;

    /** @var string */
    private $fromId;

    /** @var integer */
    private $amount;

    /** @var integer */
    private $toId;

    /**
     * TransferWasMadeBuilder constructor.
     */
    public function __construct()
    {
        $this->factory = Factory::create();
        $this->reset();
    }

    /**
     * @return TransferWasMade
     */
    public function build(): TransferWasMade
    {
        $event = new TransferWasMade(
            MemberId::with($this->fromId),
            Money::MXN($this->amount),
            MemberId::with($this->toId)
        );

        $this->reset();

        return $event;
    }

    /**
     * Set random initial values for the event
     */
    protected function reset()
    {
        $this->fromId = $this->factory->uuid;
        $this->amount = $this->factory->numberBetween(1, 10000);
        $this->toId = $this->factory->uuid;
    }
}
