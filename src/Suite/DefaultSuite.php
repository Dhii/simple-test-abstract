<?php

namespace Dhii\SimpleTest\Suite;

use Dhii\SimpleTest;
use Dhii\SimpleTest\Assertion;
use Dhii\SimpleTest\Test;

class DefaultSuite extends AbstractExtended
{
    public function __construct(
            $code,
            SimpleTest\TesterInterface $tester,
            SimpleTest\RunnerInterface $runner,
            Assertion\MakerInterface $assertionMaker
    ) {
        $this->_setCode($code)
                ->_setRunner($runner);
    }
    
    /**
     * Low-level suite code setting.
     * 
     * @since [*next-version*]
     * @param string $code The suite code to set.
     * @return AbstractSuite This instance.
     */
    protected function _setCode($code)
    {
        $this->code = $code;
        return $this;
    }
    
    /**
     * 
     * 
     * @since [*next-version*]
     * @param \Dhii\SimpleTest\Test\TestInterface $test
     */
    protected function _runTest(Test\TestInterface $test)
    {
        $runner = $this->_getRunner();
        $assertionCounts = $runner->getAssertionStatusCount();
        $statusCounts = $runner->getTestStatusCount();
        $this->_beforeRunTest($runner, $test);
        $runner->run($test);
        $this->_updateAssertionStatusCounts($assertionCounts, $runner->getAssertionStatusCount());
        $this->_updateStatusCounts($statusCounts, $runner->getTestStatusCount());
        $this->_afterRunTest($runner, $test);
    }

    public function getWriter() {
        
    }

}
