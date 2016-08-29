<?php

namespace Dhii\SimpleTest\Runner;

use Dhii\SimpleTest\Coordinator;
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
     *
     * @param Coordinator\CoordinatorInterface $coordinator    The coordinator that this runner will notify.
     * @param Assertion\MakerInterface         $assertionMaker The assertion maker that test cases run by this runner will use.
     */
    public function __construct(Coordinator\CoordinatorInterface $coordinator, Assertion\MakerInterface $assertionMaker)
    {
        $this->_setCoordinator($coordinator);
        $this->_setAssertionMaker($assertionMaker);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getCode()
    {
        return 'default';
    }
}
