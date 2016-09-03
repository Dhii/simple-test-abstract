<?php

namespace Dhii\SimpleTest\Metrics;

/**
 * Something that can act as a counter.
 * 
 * A counter is something that can be incremented and decremented.
 * 
 * @since [*next-version*]
 */
interface CounterInterface extends MetricInterface
{
    /**
     * Increment the counter.
     * 
     * @since [*next-version*]
     *
     * @param int|float $count By how much to increment.
     */
    public function increment($count = 1);

    /**
     * Decrement the counter.
     * 
     * @since [*next-version*]
     *
     * @param int|float $count By how much to decrement.
     */
    public function decrement($count = 1);
}
