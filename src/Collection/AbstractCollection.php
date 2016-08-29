<?php

namespace Dhii\SimpleTest\Collection;

use Exception;
use UnexpectedValueException;

/**
 * Common functionality for collections.
 *
 * @since [*next-version*]
 */
abstract class AbstractCollection implements CollectionInterface
{
    protected $items = array();

    /**
     * {@inheritdoc}
     *
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
     *
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
     *
     * @param mixed $item The item to add.
     */
    protected function _addItem($item)
    {
        $this->items[$this->_getItemKey($item)] = $item;

        return $this;
    }

    /**
     * Set the internal items list.
     *
     * The internal list will be replaced with the one given.
     *
     * @since [*next-version*]
     *
     * @param array|\Traversable $items The item list to set.
     *
     * @return AbstractCollection This instance.
     */
    protected function _setItems($items)
    {
        $this->_validateItemList($items);
        $this->items = $items;

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
     *
     * @throws \Exception If the item is invalid;
     */
    abstract protected function _validateItem($item);

    /**
     * Determines if item is a valid member of the collection.
     *
     * @since [*next-version*]
     *
     * @param mixed $item The item to evaluate.
     *
     * @return bool True if the item is valid; false otherwise.
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

    /**
     * Throws an exception if the given value is not a valid item list.
     *
     * @since [*next-version*]
     *
     * @param mixed $items An item list.
     *
     * @throws UnexpectedValueException If the list is not a valid item list.
     */
    protected function _validateItemList($items)
    {
        if (!is_array($items) && !($items instanceof \Traversable)) {
            throw new UnexpectedValueException(sprintf('Must be a valid item list'));
        }
    }
}
