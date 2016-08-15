<?php

namespace Dhii\SimpleTest;

abstract class AbstractCase extends AbstractCaseAssertions implements CaseInterface
{
    protected $assertionMaker;
    
    public function __construct(Assertion\MakerInterface $assertionMaker)
    {
        $this->_setAssertionMaker($assertionMaker);
    }
    
    protected function _setAssertionMaker(Assertion\MakerInterface $assertionMaker)
    {
        $this->assertionMaker = $assertionMaker;
        return $this;
    }
    
    public function getAssertionMaker() {
        return $this->assertionMaker;
    }
    
    public function beforeTest() {}
    public function afterTest() {}
}
