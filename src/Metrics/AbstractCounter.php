<?php

namespace Dhii\SimpleTest\Metrics;

/**
 * Common functionality for counters.
 * 
 * @since [*next-version*]
 */
abstract class AbstractCounter extends AbstractMetric implements
    CounterInterface,
    \Countable
{
    /**
     * {@inheritdoc}
     * 
     * @since [*next-version*]
     */
    public function increment($count = 1)
    {
        $count    = $this->_normalizeValue($count);
        $value    = $this->_getValue();
        $newValue = $value + $count;

        $this->_validateValue($newValue, 'Could not increment counter by ' . $count . ': %1$s');
        $this->_setValue($value);

        return $this;
    }

    /**
     * {@inheritdoc}
     * 
     * @since [*next-version*]
     */
    public function decrement($count = 1)
    {
        $count    = $this->_normalizeValue($count);
        $value    = $this->_getValue();
        $newValue = $value - $count;

        $this->_validateValue($newValue, 'Could not decrement counter by ' . $count . ': %1$s');
        $this->_setValue($value);

        return $this;
    }
}
