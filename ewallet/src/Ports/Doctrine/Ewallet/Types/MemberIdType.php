<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ports\Doctrine\Ewallet\Types;

use Ewallet\Memberships\{Identifier, MemberId};

/**
 * UUID fields will be stored as a string in the database and converted back to
 * the MemberId value object when querying.
 */
class MemberIdType extends UuidType
{
    public function identifier(string $value): Identifier
    {
        return MemberId::withIdentity($value);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'MemberId';
    }
}
