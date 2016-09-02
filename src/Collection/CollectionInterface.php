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
     * This method may return an array. However, this is not a reliable way to convert a collection
     * to an array. For that purpose, use {@see iterator_to_array()}.
     *
     * @since [*next-version*]
     *
     * @return mixed[]|\Traversable The list of items, by original key.
     */
    public function getItems();
}
