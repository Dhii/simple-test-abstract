<?php

namespace Dhii\SimpleTest\Metrics;

/**
 * A counter metric implementation.
 * 
 * @since [*next-version*]
 */
class Counter extends AbstractCounter
{
    /**
     * @since [*next-version*]
     *
     * @param null|int|float|\Countable $default The default value for this counter.
     * @param null|int|float|\Countable $value   The initial value for this counter.
     *                                           If bounds are specified, must be within them.
     * @param type                      $lBound
     * @param type                      $uBound
     */
    public function __construct($default = null, $value = null, $lBound = null, $uBound = null)
    {
        if (!is_null($default)) {
            $this->_setDefaultValue($value);
        }

        $this->_reset();

        $this->_setLowerBound($lBound);
        $this->_setUpperBound($uBound);

        $this->_validateValue($value, sprintf('Could not create metric with initial value of %1$s: ', $this->_normalizeValue($value)) . '%1$s');
        $this->_setValue($value);

        $this->_construct();
    }

    /**
     * Parameterless constructor.
     * 
     * @since [*next-version*]
     */
    protected function _construct()
    {
    }
}
