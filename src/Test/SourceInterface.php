<?php

namespace Dhii\SimpleTest\Test;

/**
 * Something that can act as a test source.
 *
 * A test source is something that can retrieve test instances.
 *
 * @since [*next-version*]
 */
interface SourceInterface
{
    /**
     * Retrieve tests.
     *
     * @since [*next-version*]
     *
     * @return Test\TestInterface[] An array of tests.
     */
    public function getTests();
}
