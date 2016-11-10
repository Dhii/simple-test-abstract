<?php

namespace Dhii\SimpleTest\Test;

use InvalidArgumentException;
use Dhii\Collection;

/**
 * Common functionality for test sources.
 *
 * @since 0.1.0
 */
abstract class AbstractSource extends Collection\AbstractSearchableCollection implements SourceInterface
{
    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function getTests()
    {
        return $this->getItems();
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    protected function _validateItem($item)
    {
        if (!($item instanceof TestInterface)) {
            throw new InvalidArgumentException(sprintf('Item must be a valid test'));
        }
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     *
     * @param Test\TestInterface $item
     */
    protected function _getItemKey($item)
    {
        return $item->getKey();
    }
}
