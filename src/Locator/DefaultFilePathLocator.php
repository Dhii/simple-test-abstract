<?php

namespace Dhii\SimpleTest\Locator;

/**
 * A default implementation of a file path locator.
 *
 * Uses all the default implementations of dependencies.
 *
 * @since [*next-version*]
 */
class DefaultFilePathLocator extends AbstractFilePathLocator
{
    /**
     * @inheritdoc
     * @since [*next-version*]
     * @return DefaultClassLocator
     */
    protected function _createClassLocator($className)
    {
        $locator = new DefaultClassLocator();
        $locator->setClass($className);

        return $locator;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     * @return DefaultResultSet
     */
    protected function _createResultSet($items)
    {
        return new DefaultResultSet($items);
    }

    /**
     * Whether or not the extension-less basename of the file path ends with "Test".
     *
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _matchFile($file)
    {
        $file = $this->_basename($file);
        return $this->_endsWith($file, 'Test');
    }
}
