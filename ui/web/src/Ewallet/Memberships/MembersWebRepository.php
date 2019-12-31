<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Memberships;

use Adapters\Doctrine\Ewallet\Memberships\MembersRepository;

class MembersWebRepository extends MembersRepository
{
    /** @return Member[] */
    public function excluding(MemberId $senderId): array
    {
        $builder = $this->manager->createQueryBuilder();

        $builder
            ->select('m')
            ->from(Member::class, 'm')
            ->where('m.memberId <> :id')
            ->setParameter('id', $senderId)
        ;

        return $builder->getQuery()->getResult();
    }
}
