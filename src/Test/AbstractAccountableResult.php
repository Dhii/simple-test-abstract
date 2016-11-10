<?php

namespace Dhii\SimpleTest\Test;

use Dhii\SimpleTest\Assertion;

/**
 * Common functionality for test results that can have their assertion and usage stats retrieved.
 *
 * @since 0.1.0
 */
class AbstractAccountableResult extends AbstractResult implements
    UsageAccountableInterface,
    AccountableInterface,
    Assertion\AccountableInterface
{
    protected $timeTaken;
    protected $memoryTaken;

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function getTimeTaken()
    {
        return $this->timeTaken;
    }

    /**
     * Set the amount of time taken to run the test.
     *
     * @since 0.1.0
     *
     * @param int $seconds The number of seconds taken to run the test.
     *
     * @return AbstractResult This instance.
     */
    protected function _setTimeTaken($seconds)
    {
        $this->timeTaken = $seconds;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function getMemoryTaken()
    {
        return $this->memoryTaken;
    }

    /**
     * Set the amount of memory allocated to run the test.
     *
     * @since 0.1.0
     *
     * @param int $bytes The number of bytes that was taken to run the test.
     *
     * @return AbstractResult This instance.
     */
    protected function _setMemoryTaken($bytes)
    {
        $this->memoryTaken = $bytes;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function getTestCount()
    {
        return 1;
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function getTestCountByStatus($status)
    {
        return $this->getStatus() === $status
                ? 1
                : 0;
    }
}
