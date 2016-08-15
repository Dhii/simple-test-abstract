<?php

namespace Dhii\SimpleTest;

interface CaseAssertionsInterface
{
    public function assert($assertion, $message);
    public function assertTrue($value, $message);
    public function assertFalse($value, $message);
}
