<?php

namespace Dhii\SimpleTest\Writer;

/**
 * Something that can act like it's writer-aware.
 *
 * That means that instances of this class can have a writer retrieved.
 *
 * @since [*next-version*]
 */
interface WriterAwareInterface
{
    /**
     * Retrieves the writer used by this instance.
     *
     * @since [*next-version*]
     *
     * @return WriterInterface
     */
    public function getWriter();
}
