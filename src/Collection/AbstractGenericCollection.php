<?php

namespace Dhii\SimpleTest\Collection;

/**
 * A default implementation of a general purpose collection.
 *
 * @since [*next-version*]
 */
abstract class AbstractGenericCollection extends AbstractSearchableCollection
{
    public function __construct($items = null)
    {
        if (!is_null($items)) {
            $this->_addItems($items);
        }
    }

    public function _validateItem($item)
    {
        return true;
    }
}
