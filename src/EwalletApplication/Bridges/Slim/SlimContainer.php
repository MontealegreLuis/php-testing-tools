<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Slim;

use Pimple\Container;
use Slim\Helper\Set;

class SlimContainer extends Set
{
    /** @var Container */
    private $pimple;

    /**
     * @param Container $pimple
     */
    public function __construct(Container $pimple)
    {
        $this->pimple = $pimple;
    }

    /**
     * @param  string $key The data key
     * @param  mixed $default The value to return if data key does not exist
     * @return mixed           The data value, or the default value
     */
    public function get($key, $default = null)
    {
        if ($value = parent::get($key, $default)) {
            return $value;
        }

        return $this->pimple[$key];
    }

    /**
     * @param Set $container
     * @return SlimContainer
     */
    public function merge(Set $container)
    {
        $this->data = $container->data;

        return $this;
    }
}
