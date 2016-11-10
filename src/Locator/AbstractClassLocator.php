<?php

namespace Dhii\SimpleTest\Locator;

use ReflectionMethod;
use ReflectionClass;
use InvalidArgumentException;
use RuntimeException;

/**
 * Common functionality for test locators that find tests in classes.
 *
 * @since 0.1.0
 */
abstract class AbstractClassLocator extends AbstractLocator implements ClassLocatorInterface
{
    protected $class;

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     *
     * @throws RuntimeException If class not specified.
     */
    public function locate()
    {
        if (!($class = $this->_getClass())) {
            throw new RuntimeException(sprintf('Could not locate tests from class: Class must be specified'));
        }

        return $this->_createResultSet($this->_getClassTests($class));
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     *
     * @throws InvalidArgumentException If class is not a class name or reflection.
     */
    public function setClass($class)
    {
        if (!is_string($class) && !($class instanceof ReflectionClass)) {
            throw new InvalidArgumentException(sprintf('Could not set class for class locator: Class name or reflection required'));
        }

        $this->class = $class;
    }

    /**
     * Retrieve the reflection of the class, in which to locate the tests.
     *
     * @since 0.1.0
     *
     * @return ReflectionClass|null A reflection of the class, in which to locate test methods, if class specified;
     *                              otherwise, null;
     */
    protected function _getClass()
    {
        if (empty($this->class)) {
            return;
        }

        if ($this->class instanceof ReflectionClass) {
            return $this->class;
        }

        if (!class_exists($this->class)) {
            throw new RuntimeException(sprintf('Could not get class for class locator: Class "%1$s" does not exist', $this->class));
        }

        return new ReflectionClass($this->class);
    }

    /**
     * Creates a test instance from a class and method name.
     *
     * @since 0.1.0
     *
     * @param string      $className  Name of the test case class.
     * @param string      $methodName Name of the test method.
     * @param string|null $key        The key of the test.
     *
     * @return Test\Test\TestInterface
     */
    abstract protected function _createTest($className, $methodName, $key);

    /**
     * Generates a test identifier.
     *
     * @since 0.1.0
     *
     * @param string $className  Name of the test case class.
     * @param string $methodName Name of the test method.
     *
     * @return string A suite-wide unique test identifier.
     */
    protected function _generateTestKey($className, $methodName)
    {
        $key = sprintf('%1$s#%2$s', $className, $methodName);

        return $key;
    }

    /**
     * @since 0.1.0
     *
     * @param ReflectionClass $class The reflection of the class, for which to get the tests.
     *                               That class must be a descendant of {@see CaseInterface}.
     *
     * @return Test\TestInterface[]
     */
    protected function _getClassTests(ReflectionClass $class)
    {
        $methods = array();
        foreach ($class->getMethods() as $_method) {
            /* @var $_method ReflectionMethod */
            if (!$this->_matchMethod($_method)) {
                continue;
            }

            $key                      = $this->_generateTestKey($class->getName(), $_method->getName());
            $test                     = $this->_createTest($class->getName(), $_method->getName(), $key);
            $methods[$test->getKey()] = $test;
        }

        return $methods;
    }

    /**
     * Determines if a method is a valid test method.
     *
     * @since 0.1.0
     *
     * @return bool True if the method matches; false otherwise.
     */
    abstract protected function _matchMethod(ReflectionMethod $method);

    /**
     * Retrieve a reflection of a class by its name or from an instance.
     *
     * @since 0.1.0
     *
     * @param object|string $object An object, or a class name.
     *
     * @return \ReflectionClass The reflection instance.
     */
    protected function _getObjectReflection($object)
    {
        return new ReflectionClass($object);
    }
}
