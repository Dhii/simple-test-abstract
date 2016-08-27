<?php

namespace Dhii\SimpleTest\Test;

/**
 * Common functionality for test results.
 *
 * @since [*next-version*]
 */
abstract class AbstractResult extends AbstractTestBase implements ResultInterface
{
    protected $status;
    protected $message;
    protected $assertionCount;
    protected $runnerCode;
    protected $timeTaken;
    protected $memoryTaken;

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the status code for this test.
     *
     * A status represents the outcome of a test.
     *
     * @since [*next-version*]
     * @param string A status for this test.
     */
    protected function _setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the message for this test.
     *
     * @since [*next-version*]
     * @param mixed Something that represents a message.
     */
    protected function _setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getStatusCodes()
    {
        return array(
            self::ERROR,
            self::FAILURE,
            self::SUCCESS
        );
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function isSuccessful()
    {
        return $this->getStatus() === ResultInterface::SUCCESS;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getAssertionCount()
    {
        return $this->assertionCount;
    }

    /**
     * Set the amount of assertions that were made during this test.
     *
     * @since [*next-version*]
     * @param int The code of the runner that ran this test.
     */
    protected function _setAssertionCount($assertionCount)
    {
        $this->assertionCount = intval($assertionCount);
        return $this;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getRunnerCode()
    {
        return $this->runnerCode;
    }

    /**
     * Set the code of the runner that ran this test.
     *
     * @since [*next-version*]
     * @param string The code of the runner that ran this test.
     */
    protected function _setRunnerCode($code)
    {
        $this->runnerCode = $code;

        return $this;
    }

    /**
     * Sets the code name of the suite that ran the test, of which this instance represents the result.
     *
     * @param string $code The code name of the suite that ran the test.
     * @return AbstractResult This instance.
     */
    protected function _setSuiteCode($code)
    {
        $this->suiteCode = $code;

        return $this;
    }

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
     * @param int $bytes The number of bytes that was taken to run the test.
     * @return AbstractResult This instance.
     */
    protected function _setMemoryTaken($bytes)
    {
        $this->memoryTaken = $bytes;

        return $this;
    }
}
