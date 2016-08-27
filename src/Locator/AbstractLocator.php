<?php

namespace Dhii\SimpleTest\Locator;

/**
 * Common functionality for test locators.
 *
 * @since [*next-version*]
 */
abstract class AbstractLocator implements LocatorInterface
{
    /**
     * Creates a new result set with the given items.
     *
     * @since [*next-version*]
     * @return ResultSetInterface The new locator result set.
     */
    abstract protected function _createResultSet($items);
}
