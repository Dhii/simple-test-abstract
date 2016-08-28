<?php

namespace Dhii\SimpleTest\Locator;

/**
 * Something that can act as a locator, which retrieves tests from a file path.
 *
 * @since [*next-version*]
 */
interface FilePathLocatorInterface
{
    /**
     * Add a path to this locator.
     *
     * @since [*next-version*]
     * @param string|\Traversable $path A path or glob expression (no brace syntax),
     *  or an instance of an iterator which returns file paths.
     */
    public function addPath($path);
}
