<?php

namespace Dhii\SimpleTest\Assertion;

/**
 * Common functionality for assertion makers that can have their assertion stats retrieved.
 *
 * @since [*next-version*]
 */
class AbstractAccountableMaker extends AbstractMaker implements AccountableInterface
{
    protected $assertionCount = 0;

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getAssertionCount()
    {
        return $this->assertionCount;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    protected function _processAssertionResult($result)
    {
        $result = parent::_processAssertionResult($result);
        $this->assertionCount++;

        return $result;
    }
}
