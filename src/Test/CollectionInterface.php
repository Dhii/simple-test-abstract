<?php

namespace Dhii\SimpleTest\Test;

/**
 * Something that can act like a collection.
 *
 * @since [*next-version*]
 */
interface CollectionInterface
{
    /**
     * Retrieve the items of the collection.
     *
     * @since [*next-version*]
     * @return mixed[] The array of items, by original key.
     */
    public function getItems();
}
