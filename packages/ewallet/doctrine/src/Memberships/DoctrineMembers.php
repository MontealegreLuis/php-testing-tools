<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Memberships;

use Doctrine\ORM\EntityRepository;

class DoctrineMembers extends EntityRepository implements MembersRepository
{
    /**
     * @param MemberId $id
     * @return Member
     * @throws UnknownMember
     */
    public function with(MemberId $id): Member
    {
        $builder = $this->createQueryBuilder('m');

        $builder
            ->where('m.memberId = :id')
            ->setParameter('id', $id)
        ;

        if (!$member = $builder->getQuery()->getOneOrNullResult()) {
            throw UnknownMember::identifiedBy($id);
        }

        return $member;
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
     * @param MemberId $senderId
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
