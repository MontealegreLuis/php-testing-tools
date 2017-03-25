<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Memberships;

use Doctrine\ORM\EntityRepository;

class DoctrineMembers extends EntityRepository implements MembersRepository
{
    /**
     * @throws UnknownMember
     */
    public function with(MemberId $memberId): Member
    {
        $builder = $this->createQueryBuilder('m');

        $builder
            ->where('m.memberId = :id')
            ->setParameter('id', $memberId)
        ;

        if (!$member = $builder->getQuery()->getOneOrNullResult()) {
            throw UnknownMember::identifiedBy($memberId);
        }

        return $member;
    }

    public function add(Member $member): void
    {
        $this->_em->persist($member);
        $this->_em->flush($member);
    }

    public function update(Member $member): void
    {
        $this->_em->persist($member);
        $this->_em->flush($member);
    }

    /**
     * @return Member[]
     */
    public function excluding(MemberId $senderId): array
    {
        $builder = $this->createQueryBuilder('m');

        $builder
            ->where('m.memberId <> :id')
            ->setParameter('id', $senderId)
        ;

        return $builder->getQuery()->getResult();
    }
}
