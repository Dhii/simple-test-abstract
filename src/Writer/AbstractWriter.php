<?php

namespace Dhii\SimpleTest\Writer;

/**
 * Common functionality for writers.
 * 
 * @since [*next-version*]
 */
abstract class AbstractWriter implements WriterInterface
{
    const LINE_WIDTH = 80;
    const DEC_CHAR_1 = '=';
    const DEC_CHAR_2 = '#';
    const DEC_CHAR_3 = '-';
    
    const EOL = PHP_EOL;
    
    protected $level = 1;
    
    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }
    
    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getLevel()
    {
        return $this->level;
    }
    
    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function writeLine($text, $level = self::LVL_1)
    {
        $this->write($text . static::EOL, $level);
    }
    
    /**
     * Checks whether messages of a specified level should be written by this instane.
     * 
     * @since [*next-version*]
     * @param int $level The level to check.
     * @return bool True if this instance should write messages of the specified level;
     *  false otherwise.
     */
    protected function _isShouldWriteLevel($level)
    {
        return $this->getLevel() >= intval($level);
    }
    
    /**
     * Generate a simple string representation of a value.
     * 
     * This helps to understand the type and possibly a scalar value of something.
     * 
     * @since [*next-version*]
     * @param mixed $object The value to generate a simple string representation of.
     * @return string A simplified representation of the specified value.
     */
    protected function _simpleDebug($object)
    {
        if (is_object($object)) {
            return sprintf('object(%1$s)', get_class($object));
        }
        
        if (is_string($object)) {
            return sprintf('string(%1$s)', strlen($object));
        }
        
        if (is_numeric($object)) {
            return sprintf('number(%1$s)', $object+0);
        }
        
        return gettype($object);
    }
}
