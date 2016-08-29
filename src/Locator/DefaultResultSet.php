<?php

namespace Dhii\SimpleTest\Locator;

use Dhii\SimpleTest\Test;
use InvalidArgumentException;

/**
 * A default locator result set implementation.
 *
 * @since [*next-version*]
 */
class DefaultResultSet extends AbstractResultSet
{
    /**
     * @since [*next-version*]
     *
     * @param Test\TestInterface[]|\Traversable $items The items for this set.
     */
    public function __construct($items)
    {
        $this->_addItems($items);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _validateItem($item)
    {
        if (!($item instanceof Test\TestInterface)) {
            throw new InvalidArgumentException(sprintf('Not a valid test'));
        }
    }
}
