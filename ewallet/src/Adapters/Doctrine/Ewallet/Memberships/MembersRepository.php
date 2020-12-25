<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Doctrine\Ewallet\Memberships;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Ewallet\Memberships\Member;
use Ewallet\Memberships\MemberId;
use Ewallet\Memberships\Members;
use Ewallet\Memberships\UnknownMember;

/**
 * @noRector Rector\SOLID\Rector\Class_\FinalizeClassesWithoutChildrenRector
 */
class MembersRepository implements Members
{
    protected EntityManager $manager;

    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @throws UnknownMember
     * @throws NonUniqueResultException
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

    public function save(Member $member): void
    {
        $this->manager->persist($member);
        $this->manager->flush();
    }
}
