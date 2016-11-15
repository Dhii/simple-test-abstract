<?php

namespace Dhii\SimpleTest\Locator;

use Dhii\SimpleTest\Test;

/**
 * Common functionality for locator result sets.
 *
 * @since 0.1.0
 */
abstract class AbstractResultSet extends Test\AbstractSource implements ResultSetInterface
{
    /**
     * Makes the set unique by identifying each item by a key derived from the item.
     *
     * @since [*next-version*]
     */
    protected function _getItemUniqueKey($item)
    {
        return $this->_getItemKey($item);
    }
}
