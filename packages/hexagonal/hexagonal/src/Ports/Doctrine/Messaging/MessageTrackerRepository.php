<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ports\Doctrine\Messaging;

use Hexagonal\Messaging\EmptyExchange;
use Hexagonal\Messaging\InvalidPublishedMessageToTrack;
use Hexagonal\Messaging\MessageTracker;
use Hexagonal\Messaging\PublishedMessage;
use Ports\Application\DataStorage\Repository;

class MessageTrackerRepository extends Repository implements MessageTracker
{
    /** @throws \Doctrine\ORM\NonUniqueResultException */
    public function hasPublishedMessages(string $exchangeName): bool
    {
        $builder = $this->manager->createQueryBuilder();
        $builder
            ->select('COUNT(p)')
            ->from(PublishedMessage::class, 'p')
            ->where('p.exchangeName = :exchangeName')
            ->setParameter('exchangeName', $exchangeName)
        ;

        return (integer) $builder->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @throws EmptyExchange If no message has been published to this exchange
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function mostRecentPublishedMessage(string $exchangeName): PublishedMessage
    {
        $builder = $this->manager->createQueryBuilder();
        $builder
            ->select('p')
            ->from(PublishedMessage::class, 'p')
            ->where('p.exchangeName = :exchangeName')
            ->setParameter('exchangeName', $exchangeName);

        $publishedMessage = $builder->getQuery()->getOneOrNullResult();
        if (!$publishedMessage) {
            throw new EmptyExchange("$exchangeName has no published messages");
        }
        return $publishedMessage;
    }

    /**
     * @throws InvalidPublishedMessageToTrack There can only be either 0 or 1
     * entries associated with an exchange, this exception is thrown if there's
     * already a message but it is not equal to `mostRecentPublishedMessage`
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     */
    public function track(PublishedMessage $mostRecentPublishedMessage): void
    {
        $builder = $this->manager->createQueryBuilder();
        $builder
            ->select('p')
            ->from(PublishedMessage::class, 'p')
            ->where('p.exchangeName = :exchangeName')
            ->setParameter('exchangeName', $mostRecentPublishedMessage->exchangeName());

        $currentMessage = $builder->getQuery()->getOneOrNullResult();
        if ($currentMessage && !$currentMessage->equals($mostRecentPublishedMessage)) {
            throw new InvalidPublishedMessageToTrack();
        }

        $this->manager->persist($mostRecentPublishedMessage);
        $this->manager->flush($mostRecentPublishedMessage);
    }
}
