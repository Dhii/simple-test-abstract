<?php

namespace Dhii\SimpleTest\TestCase;

/**
 * Common functionality for test cases.
 *
 * Test cases are what the user-written tests must go into.
 *
 * @since [*next-version*]
 */
abstract class AbstractCase implements CaseInterface
{
    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function beforeTest()
    {
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function afterTest()
    {
    }
}
