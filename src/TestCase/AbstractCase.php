<?php

namespace Dhii\SimpleTest\TestCase;

/**
 * Common functionality for test cases.
 *
 * Test cases are what the user-written tests must go into.
 *
 * @since 0.1.0
 */
abstract class AbstractCase implements CaseInterface
{
    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function beforeTest()
    {
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function afterTest()
    {
    }
}
