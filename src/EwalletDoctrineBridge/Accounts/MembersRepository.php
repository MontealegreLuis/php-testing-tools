<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletDoctrineBridge\Accounts;

use Doctrine\ORM\EntityRepository;
use Ewallet\Accounts\Identifier;
use Ewallet\Accounts\Member;
use Ewallet\Accounts\Members;

class MembersRepository extends EntityRepository implements Members
{
    /**
     * @param Identifier $id
     * @return Member | null
     */
    public function with(Identifier $id)
    {
        $builder = $this->createQueryBuilder('m');

        $builder
            ->where('m.memberId = :id')
            ->setParameter('id', $id)
        ;

        return $builder->getQuery()->getOneOrNullResult();
    }

    /**
     * @param Member $member
     */
    public function add(Member $member)
    {
        $this->_em->persist($member);
        $this->_em->flush($member);
    }

    /**
     * @param Member $member
     */
    public function update(Member $member)
    {
        $this->_em->persist($member);
        $this->_em->flush($member);
    }

    /**
     * @param Identifier $memberId
     * @return array
     */
    public function excluding(Identifier $memberId)
    {
        $builder = $this->createQueryBuilder('m');

        $builder
            ->where('m.memberId <> :id')
            ->setParameter('id', $memberId)
        ;

        return $builder->getQuery()->getResult();
    }
}
