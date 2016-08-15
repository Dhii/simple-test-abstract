<?php

namespace Dhii\SimpleTest\Test;

use Dhii\SimpleTest\Assertion;
use Dhii\SimpleTest\Writer;
use Dhii\SimpleTest\Test;

abstract class AbstractSupervisor extends Assertion\AbstractAccountable implements
    Assertion\AccountableInterface,
    AccountableInterface,
    Writer\WriterAwareInterface
{
    protected $countByStatus = array();
    
    public function getTestStatusCount($status = null)
    {
        if (is_null($status)) {
            return $this->countByStatus;
        }
        
        return isset($this->countByStatus[$status])
                ? $this->countByStatus[$status]
                : null;
    }
    
    public function getTotalTestCount()
    {
        $total = 0;
        foreach ($this->getTestStatusCount() as $_statusCode => $_count) {
            $total += intval($_count);
        }
        
        return $total;
    }
    
    public function getTestStatusCodes()
    {
        return array_keys($this->getTestStatusMessages());
    }
    
    public function getTestStatusMessages()
    {
        return array(
            Test\TestInterface::SUCCESS      => 'OK',
            Test\TestInterface::FAILURE      => 'FAILURE',
            Test\TestInterface::ERROR        => 'ERROR'
        );
    }
    
    public function getTestStatusMessage($statusCode)
    {
        $stati = $this->getTestStatusMessages();
        return isset($stati[$statusCode])
                ? $stati[$statusCode]
                : null;
    }
    
    protected function _setStatusCount($status, $count)
    {
        $this->countByStatus[$status] = $count;
        return $this;
    }
    
    protected function _addStatusCount($status, $count = 1)
    {
        $currentCount = isset($this->countByStatus[$status])
                ? intval($this->countByStatus[$status])
                : 0;
        
        $this->_setStatusCount($status, $currentCount + intval($count));
        
        return $this;
    }
    
    protected function _updateStatusCounts($old, $new)
    {
        $counts = $this->_mergeCounts($old, $new);
        foreach ($counts as $_statusCode => $_count) {
            $this->_addStatusCount($_statusCode, $_count);
        }
        
        return $this;
    }
    
    protected function _addStatusCounts(AccountableInterface $accountable)
    {
        $counts = $accountable->getTestStatusCount();
        foreach ($counts as $_statusCode => $_count)
        {
            $this->_addStatusCount($_statusCode, $_count);
        }
        
        return $this;
    }
    
    protected function _mergeCounts($base, $new)
    {
        $statusCounts = array_merge($base, $new);
        foreach ($new as $_status => $_count) {
            if (array_key_exists($_status, $statusCounts)) {
                $statusCounts[$_status] = intval($new[$_status]) - intval($base[$_status]);
            }
        }
        
        return $statusCounts;
    }
}
