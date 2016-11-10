<?php

namespace Dhii\SimpleTest\TestCase;

use Dhii\SimpleTest\Assertion;

/**
 * Common functionality for test cases that can make assertions.
 *
 * @since 0.1.0
 */
abstract class AbstractCaseAssertive extends AbstractCase implements AssertiveInterface
{
    protected $assertionMaker;

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
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
     * @since 0.1.0
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
     * @since 0.1.0
     */
    public function assert($assertion, $message)
    {
        $this->_getAssertionMaker()->make($assertion, $message);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function setAssertionMaker(Assertion\MakerInterface $assertionMaker)
    {
        $this->assertionMaker = $assertionMaker;

        return $this;
    }

    /**
     * Retrieve the assertion maker used by this instance.
     *
     * @since 0.1.0
     *
     * @return Assertion\MakerInterface
     */
    protected function _getAssertionMaker()
    {
        return $this->assertionMaker;
    }
}
