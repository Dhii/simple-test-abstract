<?php

namespace Dhii\SimpleTest\TestCase;

use Dhii\SimpleTest\Assertion;

/**
 * Something that can make assertions us
 *
 * @since [*next-version*]
 */
interface AssertiveInterface
{
    /**
     * Set the assertion maker to be used for assertions by this instance.
     *
     * @since [*next-version*]
     * @param Assertion\MakerInterface $assertionMaker The assertion maker to be used by this intance to make assertions.
     */
    public function setAssertionMaker(Assertion\MakerInterface $assertionMaker);

    /**
     * Triggers specified message if the given assertion fails.
     *
     * @since [*next-version*]
     * @param callable $assertion An assertion to make.
     *  If this returns boolean true, the assertion is considered to be successful.
     *  Otherwise, the assertion is considered failed.
     * @param string $message The message to trigger if the assertion fails.
     */
    public function assert($assertion, $message);

    /**
     * Triggers specified message if the given value is not strictly true.
     *
     * @since [*next-version*]
     * @param bool $value The value that must be true.
     * @param string $message The message to trigger if the value is not true.
     */
    public function assertTrue($value, $message);

    /**
     * Triggers specified message if the given value is not strictly false.
     *
     * @since [*next-version*]
     * @param bool $value The value that must be false.
     * @param string $message The message to trigger if the value is not false.
     */
    public function assertFalse($value, $message);
}
