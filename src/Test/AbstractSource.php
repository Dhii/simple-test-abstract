<?php

namespace Dhii\SimpleTest\Test;

use InvalidArgumentException;
use Dhii\SimpleTest\Collection;

/**
 * Common functionality for test sources.
 *
 * @since [*next-version*]
 */
abstract class AbstractSource extends Collection\AbstractSearchableCollection implements SourceInterface
{
    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getTests()
    {
        return $this->getItems();
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    protected function _validateItem($item)
    {
        if (!($item instanceof TestInterface)) {
            throw new InvalidArgumentException(sprintf('Item must be a valid test'));
        }
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     * @param Test\TestInterface $item
     */
    protected function _getItemKey($item)
    {
        return $item->getKey();
    }
}
