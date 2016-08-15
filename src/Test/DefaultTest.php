<?php

namespace Dhii\SimpleTest\Test;

class DefaultTest extends AbstractTest
{
    public function __construct($caseName, $methodName, $key)
    {
        $this->_setCaseName($caseName)
                ->_setMethodName($methodName)
                ->_setKey($key);
    }
}
