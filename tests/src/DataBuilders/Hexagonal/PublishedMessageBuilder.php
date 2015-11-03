<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace DataBuilders\Hexagonal;

use Faker\Factory;
use Hexagonal\Messaging\PublishedMessage;

class PublishedMessageBuilder
{
    /** @var Factory */
    private $factory;

    /** @var string */
    private $exchangeName;

    /** @var integer */
    private $mostRecentMessageId;

    public function __construct()
    {
        $this->factory = Factory::create();
        $this->reset();
    }

    /**
     * @param string $name
     * @return PublishedMessageBuilder
     */
    public function withExchangeName($name)
    {
        $this->exchangeName = $name;

        return $this;
    }

    /**
     * @param integer $id
     * @return PublishedMessageBuilder
     */
    public function withMostRecentMessageId($id)
    {
        $this->mostRecentMessageId = $id;

        return $this;
    }

    /**
     * @return PublishedMessage
     */
    public function build()
    {
        $message = new PublishedMessage(
            $this->exchangeName, $this->mostRecentMessageId
        );

        $this->reset();

        return $message;
    }

    public function reset()
    {
        $this->exchangeName = $this->factory->word;
        $this->mostRecentMessageId = $this->factory->numberBetween(1, 10000);
    }
}
