<?php

namespace Dhii\SimpleTest\Collection;

use Exception;
use RuntimeException;
use UnexpectedValueException;

/**
 * Common functionality for collections.
 *
 * @since [*next-version*]
 */
abstract class AbstractCollection extends AbstractHasher implements CollectionInterface
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
     * @param array|\Traversable $items Items to add.
     *
     * @return AbstractCollection This instance.
     */
    protected function _addItems($items)
    {
        foreach ($items as $_key => $_item) {
            $this->_validateItem($_item);
            $this->_addItem($_item);
        }

        return $this;
    }

    /**
     * Add an item to the collection.
     *
     * @since [*next-version*]
     *
     * @param mixed $item The item to add.
     *
     * @return bool True if item successfully added; false if adding failed.
     */
    protected function _addItem($item)
    {
        $key = $this->_getItemUniqueKey($item);

        return $this->_arraySet($this->items, $item, $key);
    }

    /**
     * Sets an item at the specified key in this collection.
     *
     * @since [*next-version*]
     *
     * @param string $key  The key, at which to set the item
     * @param mixed  $item The item to set.
     *
     * @return bool True if item successfully set; false if setting failed.
     */
    protected function _setItem($key, $item)
    {
        return $this->_arraySet($this->items, $key, $key);
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

    /**
     * Removes the given item from this collection.
     *
     * @since [*next-version*]
     *
     * @param mixed $item The item to remove.
     *
     * @return bool True if removal successful; false if failed.
     */
    protected function _removeItem($item)
    {
        $key = $this->_getItemKey($item);

        return $this->_arrayUnset($this->items, $key);
    }

    /**
     * Checks whether the given item exists in this collection.
     *
     * @since [*next-version*]
     *
     * @param mixed $item The item to check for.
     *
     * @return bool True if the given item exists in this collection; false otherwise.
     */
    protected function _hasItem($item)
    {
        $key = $this->_getItemKey($item);

        return $this->_arrayKeyExists($this->items, $key);
    }

    /**
     * Checks whether an item with the specified key exists in this collection.
     *
     * @since [*next-version*]
     *
     * @param int|string $key The key to check for.
     *
     * @return bool True if the key exists in this collection; false otherwise.
     */
    protected function _hasItemKey($key)
    {
        return $this->_arrayKeyExists($this->items, $key);
    }

    /**
     * Retrieve an item with the specified key from this collection.
     *
     * @since [*next-version*]
     *
     * @param string|int $key     The key to get an item for.
     * @param mixed      $default The value to return if the specified key does not exists.
     *
     * @return mixed|null The item at the specified key, if it exists; otherwise, the default value.
     */
    protected function _getItem($key, $default = null)
    {
        return $this->_arrayGet($this->_getCachedItems(), $key);
    }

    /**
     * Get the key of an item to use for consistency checks.
     *
     * @since [*next-version*]
     *
     * @param mixed $item Get the key of an item.
     *
     * @return string|int The key of an item.
     */
    protected function _getItemKey($item)
    {
        return $this->_hash($item);
    }

    protected function _getItemUniqueKey($item)
    {
        return count($this->items);
    }
    /**
     * Checks if an item with the specified key exists in a list.
     *
     * @since [*next-version*]
     *
     * @param array|\ArrayAccess|AccessibleCollectionInterface $list The list to check.
     * @param string|int                                       $key  The key to check for.
     *
     * @throws RuntimeException If list is not something that can have a key checked.
     *
     * @return bool True if an item with the specified key exists the given list; otherwise false.
     */
    protected function _arrayKeyExists(&$list, $key)
    {
        if (is_array($list)) {
            return array_key_exists($key, $list);
        }

        if ($list instanceof \ArrayAccess) {
            return $list->offsetExists($key);
        }

        if ($list instanceof AccessibleCollectionInterface) {
            return $list->hasItemKey($key);
        }

        throw new RuntimeException(sprintf(
            'Could not check list for key "%1$s": the list is not something that can have an item checked', $key));
    }

    /**
     * Retrieves an item with the specified key from the given list.
     *
     * @since [*next-version*]
     *
     * @param array|\ArrayAccess|AccessibleCollectionInterface $list The list to retrieve from.
     * @param string|int                                       $key  The key to retrieve the item for.
     *
     * @throws RuntimeException If list is not something that can have a value retrieved by key.
     *
     * @return mixed|null The item at the specified key.
     */
    protected function _arrayGet(&$list, $key, $default = null)
    {
        if (is_array($list)) {
            return isset($list[$key])
                ? $list[$key]
                : $default;
        }

        if ($list instanceof \ArrayAccess) {
            return $list->offsetExists($key)
                ? $list->offsetGet($key)
                : $default;
        }

        if ($list instanceof AccessibleCollectionInterface) {
            return $list->hasItemKey($key)
                ? $list->getItem($key)
                : $default;
        }

        throw new RuntimeException(sprintf(
            'Could not get list item for key "%1$s": the list is not something that can have an item retrieved', $key));
    }

    /**
     * Set an item at the specified key in the given list.
     *
     * @since [*next-version*]
     *
     * @param mixed[]|\ArrayAccess|MutableCollectionInterface $list The list, for which to set the value.
     * @param mixed                                           $item The item to set for the specified key.
     * @param string                                          $key  The key, for which to set the item.
     *
     * @throws RuntimeException If list is not something that can have a value set.
     *
     * @return bool True if the value has been successfully set; false if setting failed.
     */
    protected function _arraySet(&$list, $item, $key)
    {
        if (is_array($list)) {
            $list[$key] = $item;

            return true;
        }

        if ($list instanceof \ArrayAccess) {
            $list->offsetSet($key, $item);

            return true;
        }

        if ($list instanceof MutableCollectionInterface) {
            return $list->setItem($item, $key);
        }

        throw new RuntimeException(sprintf(
            'Could not set list item  for key "%1$s": the list is not something that can have an item set', $key));
    }

    /**
     * Unset the specified key in the given list.
     *
     * @since [*next-version*]
     *
     * @param mixed[]|\ArrayAccess|MutableCollectionInterface $list The list, for which to set the value.
     * @param string                                          $key  The key, for which to unset the item.
     *
     * @throws RuntimeException If list is not something that can have a value unset.
     *
     * @return bool True if the value has been successfully unset; false if unsetting failed.
     */
    protected function _arrayUnset(&$array, $key)
    {
        if (is_array($array)) {
            if (isset($array[$key])) {
                unset($array[$key]);

                return true;
            }

            false;
        }

        if ($array instanceof \ArrayAccess) {
            if ($array->offsetExists($key)) {
                $array->offsetUnset($key);

                return true;
            }

            return false;
        }

        if ($array instanceof MutableCollectionInterface) {
            return $array->removeItemByKey($key);
        }

        throw new RuntimeException(sprintf(
            'Could not unset list item for key "%1$s": the list is not something that can have an item unset', $key));
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
