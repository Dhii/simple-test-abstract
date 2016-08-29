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
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getCaseName()
    {
        return $this->caseName;
    }

    /**
     * Sets the name of the test case class for this test.
     *
     * @since [*next-version*]
     *
     * @param string $name Name of the case, to which this test belongs.
     *
     * @return AbstractTest This instance.
     */
    protected function _setCaseName($name)
    {
        $this->caseName = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * Sets the name of the method for this test.
     *
     * @since [*next-version*]
     *
     * @param string $name Name of the method, which this case represents.
     *
     * @return AbstractTest This instance.
     */
    protected function _setMethodName($name)
    {
        $this->methodName = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set the key of this tets.
     *
     * @since [*next-version*]
     *
     * @param string $key Name of the method, which this case represents.
     *
     * @return AbstractTest This instance.
     */
    protected function _setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getSuiteCode()
    {
        return $this->suiteCode;
    }

    /**
     * Set the code for this test's suite.
     *
     * @since [*next-version*]
     *
     * @param string The code of the suite to which this case belongs.
     *
     * @return AbstractTestBase This instance.
     */
    public function setSuiteCode($code)
    {
        $this->suiteCode = $code;

        return $this;
    }
}
