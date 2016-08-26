<?php

namespace Dhii\SimpleTest\Test;

/**
 * Common functionality for test-representing objects.
 *
 * @since [*next-version*]
 */
abstract class AbstractTestBase implements TestInterface
{
    protected $caseName;
    protected $methodName;
    protected $suiteCode;
    protected $key;

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
    public function getSuiteCode()
    {
        return $this->suiteCode;
    }

    /**
     * Set the code for this test's suite.
     *
     * @param string The code of the suite to which this case belongs.
     * @since [*next-version*]
     */
    public function setSuiteCode($code)
    {
        $this->suiteCode = $code;

        return $this;
    }
}
