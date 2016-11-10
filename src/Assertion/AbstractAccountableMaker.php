<?php

namespace Dhii\SimpleTest\Assertion;

/**
 * Common functionality for assertion makers that can have their assertion stats retrieved.
 *
 * @since 0.1.0
 */
class AbstractAccountableMaker extends AbstractMaker implements AccountableInterface
{
    protected $assertionCount = 0;

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function getAssertionCount()
    {
        return $this->assertionCount;
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    protected function _processAssertionResult($result)
    {
        $result = parent::_processAssertionResult($result);
        ++$this->assertionCount;

        return $result;
    }
}
