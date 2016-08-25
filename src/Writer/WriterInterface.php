<?php

namespace Dhii\SimpleTest\Writer;

/**
 * Something that can act as a writer.
 * 
 * A writer is something that is responsible for outputting data.
 * 
 * @since [*next-version*]
 */
interface WriterInterface
{
    const LVL_0 = 0;
    const LVL_1 = 1;
    const LVL_2 = 2;
    const LVL_3 = 3;
    
    /**
     * Set this writer's verbosity level.
     * 
     * A verbosity determines what data gets output, and what data doesn't.
     * 
     * @since [*next-version*]
     * @param int $level The verbosity level for this writer.
     */
    public function setLevel($level);
    
    /**
     * Retrieve the verbosity level of this writer.
     * 
     * @since [*next-version*]
     * @return int This writer's verbosity level.
     */
    public function getLevel();
    
    /**
     * Output some data.
     * 
     * @since [*next-version*]
     * @param string $text The data to output.
     * @param int $level The verbosity level of the data.
     */
    public function write($text, $level = null);
    
    /**
     * Output some data with a new line at the end.
     * 
     * @see write()
     * @param string $text The data to output.
     * @param int $level The verbosity level of the data.
     */
    public function writeLine($text, $level = null);
    
    /**
     * Output a level 2 heading.
     * 
     * @see write()
     * @param string $text The data to output.
     * @param int $level The verbosity level of the data.
     */
    public function writeH2($text, $level = null);
    
    /**
     * Output a level 4 heading.
     * 
     * @see write()
     * @param string $text The data to output.
     * @param int $level The verbosity level of the data.
     */
    public function writeH4($text, $level = null);
}
