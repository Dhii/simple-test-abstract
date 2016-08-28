<?php

namespace Dhii\SimpleTest\Coordinator;

use Dhii\SimpleTest\Writer;
use Dhii\SimpleTest\Test;

/**
 * A default handling implementation.
 *
 * @since [*next-version*]
 */
class DefaultCoordinator extends AbstractCoordinator
{
    protected $writer;

    /**
     * @param Writer\WriterInterface $writer The writer to be used by this instance.
     */
    public function __construct(Writer\WriterInterface $writer)
    {
        $this->_setWriter($writer);
    }

    /**
     * Retrieve the writer used by this instance.
     *
     * @since [*next-version*]
     * @return Writer\WriterInterface The writer used by this instance.
     */
    protected function _getWriter()
    {
        return $this->writer;
    }

    /**
     * Sets a writer to be used by this instance.
     *
     * @since [*next-version*]
     * @param Writer\WriterInterface $writer The writer to be used by this instance.
     * @return DefaultCoordinator This instance.
     */
    protected function _setWriter(Writer\WriterInterface $writer)
    {
        $this->writer = $writer;

        return $this;
    }
}
