<?php

namespace Dhii\SimpleTest\Assertion;

use Dhii\SimpleTest;

/**
 * Represents an exception that occurs if an assertion fails.
 *
 * @since 0.1.0
 */
class FailedException extends SimpleTest\Exception implements FailedExceptionInterface
{
}
