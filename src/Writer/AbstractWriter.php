<?php

namespace Dhii\SimpleTest\Writer;

/**
 * Common functionality for writers.
 *
 * @since 0.1.0
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
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function writeLine($text, $level = self::LVL_1)
    {
        $this->write($text . $this->_getEol(), $level);
    }

    /**
     * Retrieve the EOL (end of line) sequence that is used by this instance.
     *
     * @since 0.1.0
     *
     * @return string The string that signifies EOL.
     */
    protected function _getEol()
    {
        return static::EOL;
    }

    /**
     * Checks whether messages of a specified level should be written by this instane.
     *
     * @since 0.1.0
     *
     * @param int $level The level to check.
     *
     * @return bool True if this instance should write messages of the specified level;
     *              false otherwise.
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
     * @codeCoverageIgnore
     *
     * @since 0.1.0
     *
     * @param mixed $object The value to generate a simple string representation of.
     *
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
            return sprintf('number(%1$s)', $object + 0);
        }

        return gettype($object);
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function write($text, $level = self::LVL_1)
    {
        if ($this->_isShouldWriteLevel($level)) {
            $this->_write($text);
        }

        return $this;
    }

    /**
     * Write the text to the output channel.
     *
     * @since 0.1.0
     */
    abstract protected function _write($text);
}
