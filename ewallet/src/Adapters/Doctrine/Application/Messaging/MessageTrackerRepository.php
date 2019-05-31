<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Doctrine\Application\Messaging;

use Application\Messaging\EmptyExchange;
use Application\Messaging\InvalidPublishedMessageToTrack;
use Application\Messaging\MessageTracker;
use Application\Messaging\PublishedMessage;
use Adapters\Doctrine\Application\DataStorage\Repository;

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
        if ($publishedMessage === null) {
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
        if ($currentMessage !== null && !$currentMessage->equals($mostRecentPublishedMessage)) {
            throw InvalidPublishedMessageToTrack::isNotTheMostRecent($mostRecentPublishedMessage, $currentMessage);
        }

        $this->manager->persist($mostRecentPublishedMessage);
        $this->manager->flush($mostRecentPublishedMessage);
    }
}