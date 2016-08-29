<?php

namespace Dhii\SimpleTest\TestCase;

use Dhii\SimpleTest\Assertion;

/**
 * Common functionality for test cases that can make assertions.
 *
 * @since [*next-version*]
 */
abstract class AbstractCaseAssertive extends AbstractCase implements AssertiveInterface
{
    protected $assertionMaker;

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function assertTrue($value, $message)
    {
        $this->assert(function () use ($value) {
            return $value === true;
        }, $message);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function assertFalse($value, $message)
    {
        $this->assert(function () use ($value) {
            return $value === false;
        }, $message);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function assert($assertion, $message)
    {
        $this->_getAssertionMaker()->make($assertion, $message);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function setAssertionMaker(Assertion\MakerInterface $assertionMaker)
    {
        $this->assertionMaker = $assertionMaker;

        return $this;
    }

    /**
     * Retrieve the assertion maker used by this instance.
     *
     * @since [*next-version*]
     *
     * @return Assertion\MakerInterface
     */
    protected function _getAssertionMaker()
    {
        return $this->assertionMaker;
    }
}
