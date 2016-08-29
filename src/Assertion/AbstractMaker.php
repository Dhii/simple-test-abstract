<?php

namespace Dhii\SimpleTest\Assertion;

use Dhii\SimpleTest;

/**
 * Common functionality for assertion makers.
 *
 * @since [*next-version*]
 */
abstract class AbstractMaker implements MakerInterface
{
    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     *
     * @throws SimpleTest\Exception If assertion is not callable.
     *
     * @return AbstractMaker This instance.
     */
    public function make($assertion, $message)
    {
        if (!is_callable($assertion)) {
            throw new SimpleTest\Exception('Could not make assertion: Assertion must be callable');
        }
        $result       = call_user_func_array($assertion, array());
        $isSuccessful = $this->_processAssertionResult($result);

        if (!$isSuccessful) {
            $this->_failAssertion($message);
        }

        return $this;
    }

    /**
     * Process a made assertion according to its result.
     *
     * @since [*next-version*]
     *
     * @param mixed $result A result of a made assertions.
     *
     * @return bool True if the assertion was successful; false otherwise.
     */
    protected function _processAssertionResult($result)
    {
        $status = $result === true
                ? self::SUCCESS
                : self::FAILURE;

        return $status === self::SUCCESS;
    }

    /**
     * React to a failed assertion with the specified message.
     *
     * @since [*next-version*]
     *
     * @param string $message The message for the failure exception.
     *
     * @throws FailedException
     */
    protected function _failAssertion($message)
    {
        throw new FailedException($message);
    }
}
