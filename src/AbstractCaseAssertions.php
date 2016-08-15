<?php
namespace Dhii\SimpleTest;

abstract class AbstractCaseAssertions implements CaseAssertionsInterface
{
    
    public function assertTrue($value, $message)
    {
        $this->assert(function () use ($value) {
            return $value === true;
        }, $message);
    }
    
    public function assertFalse($value, $message)
    {
        $this->assert(function () use ($value) {
            return $value === false;
        }, $message);
    }
    
    public function assert($assertion, $message)
    {
        $this->getAssertionMaker()->make($assertion, $message);
        
        return $this;
    }
    
    /**
     * @return Assertion\MakerInterface
     */
    abstract public function getAssertionMaker();
}
