<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ports\Doctrine\Ewallet\Memberships;

use Doctrine\ORM\EntityManagerInterface;
use Ewallet\Memberships\Member;
use Ewallet\Memberships\MemberId;
use Ewallet\Memberships\Members;
use Ewallet\Memberships\UnknownMember;

class MembersRepository implements Members
{
    /** @var EntityManagerInterface */
    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @throws UnknownMember
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function with(MemberId $memberId): Member
    {
        $builder = $this->manager->createQueryBuilder();

        $builder
            ->select('m')
            ->from(Member::class, 'm')
            ->where('m.memberId = :id')
            ->setParameter('id', $memberId)
        ;

        $member = $builder->getQuery()->getOneOrNullResult();
        if ($member === null) {
            throw UnknownMember::identifiedBy($memberId);
        }

        return $member;
    }

    public function add(Member $member): void
    {
        $this->manager->persist($member);
        $this->manager->flush();
    }

    public function update(Member $member): void
    {
        $this->manager->persist($member);
        $this->manager->flush();
    }
}
