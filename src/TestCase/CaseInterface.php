<?php

namespace Dhii\SimpleTest\TestCase;

/**
 * Something that can act as a test case.
 *
 * A test case is a class that contains test methods written by users.
 *
 * @since [*next-version*]
 */
interface CaseInterface
{
    /**
     * Executed before a test method of this instance is run.
     *
     * @since [*next-version*]
     */
    public function beforeTest();

    /**
     * Executed before a test method of this instance is run.
     *
     * @since [*next-version*]
     */
    public function afterTest();
}
