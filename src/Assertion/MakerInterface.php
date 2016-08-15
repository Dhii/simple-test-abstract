<?php

namespace Dhii\SimpleTest\Assertion;

interface MakerInterface extends AccountableInterface
{
    public function make($assertion, $message);
}
