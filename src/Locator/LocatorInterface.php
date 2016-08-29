<?php

namespace Dhii\SimpleTest\Locator;

use Dhii\SimpleTest\Test;

/**
 * Something that can act as a test locator.
 *
 * A test locator is responsible for finding and retrieving tests.
 *
 * @since [*next-version*]
 */
interface LocatorInterface
{
    /**
     * Gets a list of tests located.
     *
     * @since [*next-version*]
     *
     * @return Test\TestInterface[]|\Traversable A list of tests.
     */
    public function locate();
}
