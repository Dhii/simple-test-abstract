<?php

namespace Dhii\SimpleTest\Test;

/**
 * @since [*next-version*]
 */
interface UsageAccountableInterface
{
    /**
     * Retrieve the time that was taken to run this test.
     *
     * @since [*next-version*]
     * @return float The amount of time taken to run this test, in seconds.
     */
    public function getTimeTaken();

    /**
     * Retrieve the amount of memory that was taken to run this test.
     *
     * @since [*next-version*]
     * @return int The amount of memory taken to run this test, in bytes.
     */
    public function getMemoryTaken();
}
