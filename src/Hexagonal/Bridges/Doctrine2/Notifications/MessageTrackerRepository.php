<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Bridges\Doctrine2\Notifications;

use Doctrine\ORM\EntityRepository;
use Hexagonal\Notifications\MessageTracker;
use Hexagonal\Notifications\PublishedMessage;

class MessageTrackerRepository extends EntityRepository implements MessageTracker
{
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

        return $builder->getQuery()->getOneOrNullResult();
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
