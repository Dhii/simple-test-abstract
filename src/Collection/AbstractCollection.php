<?php

namespace Dhii\SimpleTest\Collection;

use Exception;

/**
 * Common functionality for collections.
 *
 * @since [*next-version*]
 */
abstract class AbstractCollection implements CollectionInterface
{
    protected $items = array();

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Adds items to the collection.
     *
     * @since [*next-version*]
     * @param array|\Traversable $items Items to add
     */
    protected function _addItems($items)
    {
        foreach ($items as $_key => $_item) {
            $this->_validateItem($_item);
            $this->_addItem($_item);
        }
    }

    /**
     * Add an item to the collection.
     *
     * @since [*next-version*]
     * @param mixed $item The item to add.
     */
    protected function _addItem($item)
    {
        $this->items[$this->_getItemKey($item)] = $item;

        return $this;
    }

    protected function _getItemKey($item)
    {
        return count($this->items);
    }

    /**
     * Determines if item is a valid member of the collection.
     *
     * @since [*next-version*]
     * @throws \Exception If the item is invalid;
     */
    abstract protected function _validateItem($item);

    /**
     * Determines if item is a valid member of the collection.
     *
     * @since [*next-version*]
     * @param mixed $item The item to evaluate.
     * @return boolean True if the item is valid; false otherwise.
     */
    protected function _isValidItem($item)
    {
        try {
            $this->_validateItem($item);
        } catch (Exception $ex) {
            return false;
        }

        return true;
    }
}
