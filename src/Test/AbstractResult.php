<?php

namespace Dhii\SimpleTest\Test;

/**
 * Common functionality for test results.
 *
 * @since 0.1.0
 */
abstract class AbstractResult extends AbstractTestBase implements
    ResultInterface
{
    protected $status;
    protected $message;
    protected $assertionCount;
    protected $runnerCode;

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
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
     * @since 0.1.0
     *
     * @param string A status for this test.
     */
    protected function _setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the message for this test.
     *
     * @since 0.1.0
     *
     * @param mixed Something that represents a message.
     */
    protected function _setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function getTestStatusCodes()
    {
        return static::getAllTestStatusCodes();
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public static function getAllTestStatusCodes()
    {
        return array(
            self::ERROR,
            self::FAILURE,
            self::SUCCESS,
        );
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function isSuccessful()
    {
        return $this->getStatus() === ResultInterface::SUCCESS;
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function getAssertionCount()
    {
        return $this->assertionCount;
    }

    /**
     * Set the amount of assertions that were made during this test.
     *
     * @since 0.1.0
     *
     * @param int The code of the runner that ran this test.
     */
    protected function _setAssertionCount($assertionCount)
    {
        $this->assertionCount = intval($assertionCount);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function getRunnerCode()
    {
        return $this->runnerCode;
    }

    /**
     * Set the code of the runner that ran this test.
     *
     * @since 0.1.0
     *
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
     * @since 0.1.0
     *
     * @param string $code The code name of the suite that ran the test.
     *
     * @return AbstractResult This instance.
     */
    protected function _setSuiteCode($code)
    {
        $this->suiteCode = $code;

        return $this;
    }
}
