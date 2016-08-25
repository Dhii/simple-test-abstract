<?php

namespace Dhii\SimpleTest\Test;

/**
 * Common functionality for tests.
 *
 * @since [*next-version*]
 */
abstract class AbstractTest implements TestInterface
{
    protected $caseName;
    protected $methodName;
    protected $status;
    protected $key;
    protected $message;
    protected $assertionCount;
    protected $suiteCode;
    protected $runnerCode;

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getCaseName()
    {
        return $this->caseName;
    }

    /**
     * @param string $name Name of the case, to which this test belongs.
     * @return AbstractTest This instance.
     */
    protected function _setCaseName($name)
    {
        $this->caseName = $name;
        return $this;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * @param string $name Name of the method, which this case represents.
     * @return AbstractTest This instance.
     */
    protected function _setMethodName($name)
    {
        $this->methodName = $name;
        return $this;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key Name of the method, which this case represents.
     * @return AbstractTest This instance.
     */
    protected function _setKey($key)
    {
        $this->key = $key;
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
    public function setMessage($message)
    {
        $this->message = $message;
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
     * @inheritdoc
     * @since [*next-version*]
     */
    public function isSuccessful()
    {
        return $this->getStatus() === self::SUCCESS;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function setAssertionCount($assertionCount)
    {
        $this->assertionCount = intval($assertionCount);
        return $this;
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
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getSuiteCode()
    {
        return $this->suiteCode;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function setSuiteCode($code)
    {
        $this->suiteCode = $code;

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
     * @inheritdoc
     * @since [*next-version*]
     */
    public function setRunnerCode($code)
    {
        $this->runnerCode = $code;

        return $this;
    }
}
