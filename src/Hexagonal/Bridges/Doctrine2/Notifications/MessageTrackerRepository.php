<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Bridges\Doctrine2\Notifications;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Hexagonal\Notifications\EmptyExchange;
use Hexagonal\Notifications\MessageTracker;
use Hexagonal\Notifications\PublishedMessage;

class MessageTrackerRepository extends EntityRepository implements MessageTracker
{
    /**
     * @param string $exchangeName
     * @return boolean
     */
    public function hasPublishedMessages($exchangeName)
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
     * @param string $exchangeName
     * @return PublishedMessage
     */
    public function mostRecentPublishedMessage($exchangeName)
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
     * @param PublishedMessage $mostRecentPublishedMessage
     */
    public function track(PublishedMessage $mostRecentPublishedMessage)
    {
        $this->_em->persist($mostRecentPublishedMessage);
        $this->_em->flush($mostRecentPublishedMessage);
    }
}
