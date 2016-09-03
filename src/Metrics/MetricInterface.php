<?php

namespace Dhii\SimpleTest\Metrics;

/**
 * Something that can act as a metric.
 * 
 * A metric is something that measures something.
 * 
 * @since [*next-version*]
 */
interface MetricInterface extends \Countable
{
    /**
     * Get this metric's value.
     * 
     * @since [*next-version*]
     *
     * @return int|float The value of this metric.
     */
    public function getValue();

    /**
     * Retrieve this metric's base value.
     * 
     * A base value determines the value of the metric. In other words,
     * the real value of the metric is how many times the base value of the
     * metric fits into it.
     * 
     * @since [*next-version*]
     *
     * @return float|int The base value of this metric.
     */
    public function getBaseValue();

    /**
     * Get the minimal value of this metric.
     * 
     * @since [*next-version*]
     *
     * @return int|float The minimal value that this metric can have.
     */
    public function getLowerBound();

    /**
     * Get the maximal value of this metric.
     * 
     * @since [*next-version*]
     *
     * @return int|float The maximal value that this metric can have.
     */
    public function getUpperBound();

    /**
     * Get the default value of this metric.
     * 
     * This is the value of this metric in its default state.
     * 
     * @since [*next-version*]
     *
     * @return int|float The default value of this metric.
     */
    public function getDefaultValue();

    /**
     * Reset the metric to its default state.
     * 
     * @since [*next-version*]
     */
    public function reset();
}
