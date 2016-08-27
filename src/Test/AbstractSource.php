<?php

namespace Dhii\SimpleTest\Test;

/**
 * Common functionality for test sources.
 * 
 * @since [*next-version*]
 */
abstract class AbstractSource extends AbstractCollection implements SourceInterface
{
    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    protected function _getItems()
    {
        return $this->getTests();
    }
}
