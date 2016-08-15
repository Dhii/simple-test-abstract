<?php

namespace Dhii\SimpleTest\Assertion;

abstract class AbstractAccountable implements AccountableInterface
{
    protected $assertionStatusCount = array();
    
    public function getAssertionStatusCount($status = null)
    {
        if (is_null($status)) {
            return $this->assertionStatusCount;
        }
        
        return isset($this->assertionStatusCount[$status])
                ? $this->assertionStatusCount[$status]
                : null;
    }
    
    public function getAssertionTotalCount()
    {
        $total = 0;
        foreach ($this->assertionStatusCount as $_statusCode => $_count) {
            $total += intval($_count);
        }
        
        return $total;
    }
    
    protected function _addAssertionStatusCount($status, $count = 1)
    {
        $currentCount = intval($this->getAssertionStatusCount($status));
        $this->_setAssertionStatusCount($status, $currentCount + intval($count));
        
        return $this;
    }
    
    protected function _setAssertionStatusCount($status, $count)
    {
        $this->assertionStatusCount[$status] = $count;
        return $this;
    }
    
    protected function _addAssertionStatusCounts(Assertion\AccountableInterface $accountable)
    {
        foreach ($accountable->getAssertionStatusCount() as $_statusCode => $_count) {
            $this->_addAssertionStatusCount($_statusCode, $_count);
        }
        
        return $this;
    }
    
    protected function _updateAssertionStatusCounts($old, $new)
    {
        $counts = $this->_mergeCounts($old, $new);
        foreach ($counts as $_status => $_count) {
            $this->_setAssertionStatusCount($_status, $_count);
        }
    }
    
    abstract protected function _mergeCounts($base, $new);
}
