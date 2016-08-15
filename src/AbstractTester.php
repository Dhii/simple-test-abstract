<?php

namespace Dhii\SimpleTest;

abstract class AbstractTester extends Test\AbstractSupervisor implements TesterInterface
{
    protected $suites;
    protected $writer;
    
    public function __construct(Writer\WriterInterface $writer) {
        $this->_setWriter($writer);
    }
    
    protected function _setWriter(Writer\WriterInterface $writer)
    {
        $this->writer = $writer;
        return $this;
    }
    
    public function getWriter()
    {
        return $this->writer;
    }
    
    public function addSuite(SuiteInterface $suite)
    {
        $this->suites[$suite->getCode()] = $suite;
    }
    
    public function runAll()
    {
        $this->_beforeRunAll();
        
        foreach ($this->_getSuites() as $_code => $_suite) {
            /* @var $_suite SuiteInterface */
            $oldAssertionCounts = $_suite->getAssertionStatusCount();
            $oldTestStatusCounts = $_suite->getTestStatusCount();
            $this->_beforeRunSuite($_suite);
            $_suite->runAll();
            $this->_updateAssertionStatusCounts($oldAssertionCounts, $_suite->getAssertionStatusCount());
            $this->_updateStatusCounts($oldTestStatusCounts, $_suite->getTestStatusCount());
            $this->_afterRunSuite($_suite);
        }
        
        $this->_afterRunAll();
    }
    
    protected function _beforeRunSuite(SuiteInterface $_suite)
    {
        return $this;
    }
    
    protected function _afterRunSuite(SuiteInterface $_suite)
    {
        return $this;
    }
    
    protected function _afterRunAll()
    {
        return $this;
    }
    
    protected function _beforeRunAll()
    {
        return $this;
    }
    
    /**
     * @return \SuiteInterface[]
     */
    protected function _getSuites()
    {
        return $this->suites;
    }
}
