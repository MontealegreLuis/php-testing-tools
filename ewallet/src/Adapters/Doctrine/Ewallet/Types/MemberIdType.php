<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Doctrine\Ewallet\Types;

use Ewallet\Memberships\Identifier;
use Ewallet\Memberships\MemberId;

/**
 * UUID fields will be stored as a string in the database and converted back to
 * the MemberId value object when querying.
 */
class MemberIdType extends UuidType
{
    public function identifier(string $value): Identifier
    {
        return new MemberId($value);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'MemberId';
    }
}
