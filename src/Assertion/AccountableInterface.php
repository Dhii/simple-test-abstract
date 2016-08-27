<?php

namespace Dhii\SimpleTest\Assertion;

/**
 * Something that can retrieve totals for assertions made in relation to it.
 *
 * @since [*next-version*]
 */
interface AccountableInterface
{
    /**
     * Retrieve the number of assertions made durint test ran in relation to this instance.
     *
     * @since [*next-version*]
     * @return int The total number of assertions.
     */
    public function getAssertionCount();
}
