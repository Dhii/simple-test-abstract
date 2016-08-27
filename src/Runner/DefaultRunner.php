<?php

namespace Dhii\SimpleTest\Runner;

use Dhii\SimpleTest\Writer;
use Dhii\SimpleTest\Assertion;

/**
 * A default runner implementation.
 *
 * @since [*next-version*]
 */
class DefaultRunner extends AbstractRunner
{
    /**
     * @since [*next-version*]
     * @param Writer\WriterInterface $writer The writer that this runner will use to output data.
     * @param Assertion\MakerInterface $assertionMaker The assertion maker that test cases run by this runner will use.
     */
    public function __construct(Writer\WriterInterface $writer, Assertion\MakerInterface $assertionMaker)
    {
        $this->_setWriter($writer);
        $this->_setAssertionMaker($assertionMaker);
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getCode()
    {
        return 'default';
    }
}
