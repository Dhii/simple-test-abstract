<?php

namespace Dhii\SimpleTest\Collection;

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
     * @return object[] The array of items, by original key.
     */
    public function getItems();
}
