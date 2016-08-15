<?php

namespace Dhii\SimpleTest\Assertion;

interface AccountableInterface
{
    const FAILURE = 0;
    const SUCCESS = 1;
    
    public function getAssertionStatusCount($status = null);
    public function getAssertionTotalCount();
}
