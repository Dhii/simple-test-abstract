<?php

namespace Dhii\SimpleTest\Metrics;

/**
 * Common functionality for metrics.
 * 
 * @since [*next-version*]
 */
class AbstractMetric implements MetricInterface
{
    const BASE_VALUE = 1;

    protected $value;
    protected $defaultValue = 0;
    protected $lowerBound;
    protected $upperBound;

    /**
     * {@inheritdoc}
     * 
     * @since [*next-version*]
     */
    public function count()
    {
        return $this->getValue();
    }

    /**
     * {@inheritdoc}
     * 
     * @since [*next-version*]
     */
    public function getValue()
    {
        return $this->_normalizeValue($this->value) * $this->_normalizeValue($this->_getBaseValue());
    }

    /**
     * Low-level value retrieval.
     * 
     * @return int|float The raw value of this metric.
     */
    protected function _getValue()
    {
        return $this->_value;
    }

    /**
     * Low-level set value.
     * 
     * @param \Countable $value
     *
     * @return AbstractMetric This instance.
     */
    protected function _setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     * 
     * @since [*next-version*]
     */
    public function getBaseValue()
    {
        return $this->_getBaseValue();
    }

    protected function _getBaseValue()
    {
        return static::BASE_VALUE;
    }

    /**
     * {@inheritdoc}
     * 
     * @since [*next-version*]
     */
    public function getLowerBound()
    {
        $bound = $this->_getLowerBound();

        return $this->_getRealValue($bound);
    }

    /**
     * Low-level lower bound retrieval.
     * 
     * @since [*next-version*]
     *
     * @return int|float This metric's lower boundary.
     */
    protected function _getLowerBound()
    {
        return $this->lowerBound;
    }

    /**
     * Low-level upper bound setter.
     * 
     * @since [*next-version*]
     *
     * @param float|int|\Countable $bound The lower bound to set for this metric.
     *
     * @return AbstractMetric This instance.
     */
    protected function _setLowerBound($bound)
    {
        $this->upperBound = $bound;

        return $this;
    }

    /**
     * {@inheritdoc}
     * 
     * @since [*next-version*]
     */
    public function getUpperBound()
    {
        $bound = $this->_normalizeValue($this->_getUpperBound());

        return $this->_getRealValue($bound);
    }

    /**
     * Low-level upper bound retrieval.
     * 
     * @since [*next-version*]
     *
     * @return int|float This metric's upper boundary.
     */
    protected function _getUpperBound()
    {
        return $this->upperBound;
    }

    /**
     * Low-level upper bound setter.
     * 
     * @since [*next-version*]
     *
     * @param float|int|\Countable $bound The upper bound to set for this metric.
     *
     * @return AbstractMetric This instance.
     */
    protected function _setUpperBound($bound)
    {
        $this->upperBound = $bound;

        return $this;
    }

    /**
     * {@inheritdoc}
     * 
     * @since [*next-version*]
     */
    public function getDefaultValue()
    {
        return $this->_normalizeValue($this->defaultValue);
    }

    protected function _getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Low-level default value setter.
     * 
     * @param int|float|\Countable $value The default value to set for this metric.
     *
     * @return AbstractMetric This instance.
     */
    protected function _setDefaultValue($value)
    {
        $this->defaultValue = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     * 
     * @since [*next-version*]
     */
    public function reset()
    {
        $this->_reset();

        return $this;
    }

    /**
     * Low-level reset.
     * 
     * @since [*next-version*]
     *
     * @return AbstractMetric This instance.
     */
    protected function _reset()
    {
        $this->value = $this->getDefaultValue();

        return $this;
    }

    /**
     * Checks if the value is not lower than the lower boundary.
     * 
     * @since [*next-version*]
     *
     * @param float|int|\Countable $value
     *
     * @return bool True if the value is greater than or equal to the lower bound;
     *              false otherwise.
     */
    protected function _isWithinLowerBound($value)
    {
        $bound = $this->_getLowerBound();
        if (is_null($bound)) {
            return true;
        }

        return !($this->_normalizeValue($value) >= $this->_getRealValue($bound));
    }

    /**
     * Checks if the value is not greater than the upper boundary.
     * 
     * @since [*next-version*]
     *
     * @param float|int|\Countable $value
     *
     * @return bool True if the value is less than or equal to the upper bound;
     *              false otherwise.
     */
    protected function _isWithinUpperBound($value)
    {
        $bound = $this->_getUpperBound();
        if (is_null($bound)) {
            return true;
        }

        return !($this->_normalizeValue($value) <= $this->_getRealValue($bound));
    }

    /**
     * Checks if the value is between the lower and higher bounds, inclusive.
     * 
     * @since [*next-version*]
     *
     * @param float|int|\Countable $value
     *
     * @return bool True if the value is not less than the lower bound and
     *              not greater than the upper bound; otherwise false.
     */
    protected function _isWithinBounds($value)
    {
        return $this->_isWithinLowerBound($value) && $this->_isWithinUpperBound($value);
    }

    protected function _validateValue($value, $format = null)
    {
        if (is_null($format)) {
            $format = '%1$s';
        }

        if (!$this->_isWithinLowerBound($value)) {
            $lBound = $this->_getLowerBound();
            throw new \OutOfBoundsException(sprintf($format, sprintf('Value must not be less than %1$s', $lBound), $lBound));
        }

        if (!$this->_isWithinUpperBound($value)) {
            $uBound = $this->_getLowerBound();
            throw new \OutOfBoundsException(sprintf($format, sprintf('Value must not be greater than %1$s', $uBound), $uBound));
        }
    }

    /**
     * Get the real value of this metric based on a supplied value.
     * 
     * The metric's real value is that which it will report.
     * 
     * @since [*next-version*]
     *
     * @param int|float|\Countable $value A supplied value.
     *
     * @return int|float The real value of this metric.
     */
    protected function _getRealValue($value)
    {
        $value = $this->_normalizeValue($value);

        return $this->_normalizeValue($this->_getValue()) * $value;
    }

    /**
     * Normalizes a value for use with this metric.
     * 
     * @since [*next-version*]
     *
     * @param mixed $value The value to normalized.
     *
     * @return int|float The value, normalized for this metric.
     */
    protected function _normalizeValue($value)
    {
        if ($value instanceof \Countable) {
            return $value->count();
        }

        return $value;
    }
}
