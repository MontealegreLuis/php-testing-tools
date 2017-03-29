<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Doctrine2\Messaging;

use Doctrine\ORM\{EntityRepository, NoResultException};
use Hexagonal\Messaging\{
    EmptyExchange,
    InvalidPublishedMessageToTrack,
    MessageTracker,
    PublishedMessage
};

class MessageTrackerRepository extends EntityRepository implements MessageTracker
{
    public function hasPublishedMessages(string $exchangeName): bool
    {
        $builder = $this->createQueryBuilder('p');
        $builder
            ->select('COUNT(p)')
            ->where('p.exchangeName = :exchangeName')
            ->setParameter('exchangeName', $exchangeName)
        ;

        return (integer) $builder->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @throws EmptyExchange If no message has been published to this exchange
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function mostRecentPublishedMessage(
        string $exchangeName
    ): PublishedMessage
    {
        $builder = $this->createQueryBuilder('p');
        $builder
            ->where('p.exchangeName = :exchangeName')
            ->setParameter('exchangeName', $exchangeName)
        ;
        try {
            return $builder->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            throw new EmptyExchange("$exchangeName has no published messages");
        }
    }

    /**
     * @throws InvalidPublishedMessageToTrack There can only be either 0 or 1
     * entries associated with an exchange, this exception is thrown if there's
     * already a message but it is not equal to `mostRecentPublishedMessage`
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function track(PublishedMessage $mostRecentPublishedMessage): void
    {
        $builder = $this->createQueryBuilder('p');
        $builder
            ->andWhere('p.exchangeName = :exchangeName')
            ->setParameter(
                'exchangeName',
                $mostRecentPublishedMessage->exchangeName()
            )
        ;

        $currentMessage = $builder->getQuery()->getOneOrNullResult();
        if ($currentMessage && !$currentMessage->equals($mostRecentPublishedMessage)) {
            throw new InvalidPublishedMessageToTrack();
        }

        $this->_em->persist($mostRecentPublishedMessage);
        $this->_em->flush($mostRecentPublishedMessage);
    }
}
