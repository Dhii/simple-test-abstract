<?php

namespace Dhii\SimpleTest\Locator;

use Dhii\SimpleTest\Test;

/**
 * A default implementation of a class test locator.
 *
 * @since [*next-version*]
 */
class DefaultClassLocator extends AbstractClassLocator
{
    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     *
     * @return Test\DefaultTest
     */
    protected function _createTest($className, $methodName, $key)
    {
        return new Test\DefaultTest($className, $methodName, $key);
    }

    /**
     * Checks if the given method's name starts with "test".
     *
     * @since [*next-version*]
     */
    protected function _matchMethod(\ReflectionMethod $method)
    {
        return $this->_stringStartsWith($method->getName(), 'test');
    }

    /**
     * Checks if the given string starts with the specified prefix.
     *
     * @since [*next-version*]
     *
     * @param string $string         The string to check.
     * @param string $requiredPrefix The prefix.
     *
     * @return bool True if the string starts witht the required prefix;
     *              otherwise false.
     */
    protected function _stringStartsWith($string, $requiredPrefix)
    {
        $requiredLength = strlen($requiredPrefix);
        $prefix         = substr($string, 0, $requiredLength);

        return $prefix === $requiredPrefix;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     *
     * @param array|\Traversable $items
     *
     * @return DefaultResultSet The new result set.
     */
    protected function _createResultSet($items)
    {
        return new DefaultResultSet($items);
    }
}
