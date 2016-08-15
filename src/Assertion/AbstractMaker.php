<?php

namespace Dhii\SimpleTest\Assertion;

use Dhii\SimpleTest;

abstract class AbstractMaker extends AbstractAccountable implements MakerInterface
{
    public function make($assertion, $message)
    {
        if (!is_callable($assertion)) {
            throw new SimpleTest\Exception('Could not make assertion: Assertion must be callable');
        }
        $status = call_user_func_array($assertion, array())
                ? self::SUCCESS
                : self::FAILURE;
        $this->_addAssertionStatusCount($status);
        
        if ($status !== self::SUCCESS) {
            $this->_failAssertion($message);
        }
        
        return $this;   
    }

    protected function _failAssertion($message)
    {
        throw new FailedException($message);
    }
    
    protected function _mergeCounts($base, $new)
    {
        $statusCounts = array_merge($base, $new);
        foreach ($new as $_status => $_count) {
            if (array_key_exists($statusCounts[$_status])) {
                $statusCounts[$_status] = intval($new[$_status]) - intval($base[$_status]);
            }
        }
        
        return $statusCounts;
    }
}
