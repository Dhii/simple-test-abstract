<?php

namespace Dhii\SimpleTest\Collection;

use UnexpectedValueException;

/**
 * Common functionality for callback iterators.
 *
 * @since [*next-version*]
 */
abstract class AbstractCallbackIterator extends AbstractIterableCollection implements CallbackIteratorInterface
{
    protected $callback;

    /**
     * Sets the callback that will be applied to each element of this collection.
     *
     * @since [*next-version*]
     *
     * @param callable $callback The callback for this iterator to apply.
     *
     * @return AbstractCallbackIterator This instance.
     */
    protected function _setCallback($callback)
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function current()
    {
        $item = parent::current();

        return $this->_applyCallback($this->key(), $item);
    }

    /**
     * Applies the callback to an item, and returns the result.
     *
     * See {@see CallbackIterableInterface::each()} for details about the callback.
     *
     * @since [*next-version*]
     *
     * @param string|int $key  The key of the current item.
     * @param mixed      $item The item to apply the callback to.
     *
     * @return mixed The return value of the callback.
     */
    public function _applyCallback($key, $item)
    {
        $callback = $this->getCallback();
        $this->_validateCallback($callback);

        return call_user_func_array($callback, array($key, $item));
    }

    /**
     * Determines if a value is a valid callback.
     *
     * @since [*next-version*]
     *
     * @param mixed $callback The value to check.
     *
     * @throws \Exception
     */
    protected function _validateCallback($callback)
    {
        if (!is_callable($callback)) {
            throw new UnexpectedValueException(sprintf('Could not apply callback: Callback must be callable'));
        }
    }

    /**
     * Determines if a value is a valid callback.
     *
     * @param mixed $callback The value to check.
     *
     * @return bool True if the callback is valid; false otherwise.
     */
    protected function _isValidCallback($callback)
    {
        try {
            $this->_validateCallback($callback);
        } catch (Exception $ex) {
            return false;
        }

        return true;
    }
}
