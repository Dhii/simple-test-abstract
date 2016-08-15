<?php

namespace Dhii\SimpleTest;

class Tester extends AbstractTester
{
    protected function _afterRunAll()
    {
        parent::_afterRunAll();
        $writer = $this->getWriter();
     
        if (!$this->getTotalTestCount()) {
            $writer->writeLine('No tests were ran');
            return $this;
        }
        
        if (!$this->getUnsuccessfulTestCount()) {
            $writer->writeLine('OK!');
            return $this;
        }
        
        $writer->writeLine('PROBLEMS!');
        $writer->writeLine(sprintf('%1$d failed, %2$d erred, %3$d successful',
                $this->getTestStatusCount(self::TEST_FAILURE),
                $this->getTestStatusCount(self::TEST_ERROR),
                $this->getTestStatusCount(self::TEST_SUCCESS)
        ));
    }
    
    public function getUnsuccessfulTestCount()
    {
        return $this->getTestStatusCount(self::TEST_FAILURE)
                + $this->getTestStatusCount(self::TEST_ERROR);
    }
    
    public function getSuccessfulTestCount()
    {
        return intval($this->getTestStatusCount(self::TEST_SUCCESS));
    }
}
