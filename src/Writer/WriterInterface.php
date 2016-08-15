<?php

namespace Dhii\SimpleTest\Writer;

interface WriterInterface
{
    const LVL_0 = 0;
    const LVL_1 = 1;
    const LVL_2 = 2;
    const LVL_3 = 3;
    
    public function setLevel($level);
    public function getLevel();
    public function write($text, $level = null);
    public function writeLine($text, $level = null);
    public function writeH2($text, $level = null);
    public function writeH4($text, $level = null);
}
