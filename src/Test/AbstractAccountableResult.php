<?php

namespace Dhii\SimpleTest\Test;

use Dhii\SimpleTest\Assertion;

/**
 * Common functionality for test results that can have their assertion and usage stats retrieved.
 *
 * @since [*next-version*]
 */
class AbstractAccountableResult extends AbstractResult implements
    UsageAccountableInterface,
    Assertion\AccountableInterface
{
    protected $timeTaken;
    protected $memoryTaken;

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getTimeTaken()
    {
        return $this->timeTaken;
    }

    /**
     * Set the amount of time taken to run the test.
     *
     * @since [*next-version*]
     * @param int $seconds The number of seconds taken to run the test.
     * @return AbstractResult This instance.
     */
    protected function _setTimeTaken($seconds)
    {
        $this->timeTaken = $seconds;

       return $this;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getMemoryTaken()
    {
        return $this->memoryTaken;
    }

    /**
     * Set the amount of memory allocated to run the test.
     *
     * @since [*next-version*]
     * @param int $bytes The number of bytes that was taken to run the test.
     * @return AbstractResult This instance.
     */
    protected function _setMemoryTaken($bytes)
    {
        $this->memoryTaken = $bytes;

        return $this;
    }
}
