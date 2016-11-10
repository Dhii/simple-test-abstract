<?php

namespace Dhii\SimpleTest\Locator;

use Dhii\Collection;

/**
 * Common functionality for test locators.
 *
 * @since 0.1.0
 */
abstract class AbstractLocator extends Collection\AbstractHasher implements LocatorInterface
{
    /**
     * Creates a new result set with the given items.
     *
     * @since 0.1.0
     *
     * @return ResultSetInterface The new locator result set.
     */
    abstract protected function _createResultSet($items);
}
