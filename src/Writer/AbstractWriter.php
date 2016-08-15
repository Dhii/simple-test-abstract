<?php

namespace Dhii\SimpleTest\Writer;

abstract class AbstractWriter implements WriterInterface
{
    const LINE_WIDTH = 80;
    const DEC_CHAR_1 = '=';
    const DEC_CHAR_2 = '#';
    const DEC_CHAR_3 = '-';
    
    const EOL = PHP_EOL;
    
    protected $level = 1;
    
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }
    
    public function getLevel()
    {
        return $this->level;
    }
    
    public function writeLine($text, $level = self::LVL_1)
    {
        $this->write($text . static::EOL, $level);
    }
    
    protected function _isShouldWriteLevel($level)
    {
        return $this->getLevel() >= intval($level);
    }
}
