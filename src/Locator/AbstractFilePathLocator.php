<?php

namespace Dhii\SimpleTest\Locator;

use InvalidArgumentException;
use GlobIterator;
use FilesystemIterator;
use RecursiveIterator;
use RecursiveIteratorIterator;
use RecursiveArrayIterator;

/**
 * Common functionality for path locators.
 *
 * @since 0.1.0
 */
abstract class AbstractFilePathLocator extends AbstractLocator implements FilePathLocatorInterface
{
    const FILE_READ_LIMIT = 10000;

    protected $pathSpecs = array();

    /**
     * Add a path specification to this locator.
     *
     * The locator will process this specification by resolving it to a set of
     * files, depending on the form of specification.
     *
     * @since 0.1.0
     *
     * @param string|array|\Traversable $path The path specification to add.
     *                                        - If a string is specified, it will be treated as a glob expression;
     *                                        - If an array is specified, it will be treated as a list of file names;
     *                                        - If an iterator is given, it will be iterated over;
     *                                        - If a filesystem iterator is given, the following modes will be set:
     *                                        SKIP_DOTS, FOLLOW_SYMLINKS, KEY_AS_PATHNAME, CURRENT_AS_SELF
     *                                        - If an iterator iterator is given, it will be iterated over after setting the following modes:
     *                                        SELF_FIRST, LEAVES_ONLY
     *
     * For each path that the specification resolves to, naming and recognition rules apply:
     *  - The file name must correlate to the class name, as per PSR-4.
     *  - The file name, and thus the class name, must obey criteria validated
     *      by {@see _matchFile()}.
     *  - The class may have a namespace.
     *  - Both the namespace and the class name in the file must appear within
     *      the first 10'000 characters, or as defined by static::FILE_READ_LIMIT,
     *      of the file content. If they appear after that, the file name and
     *      namespace will not be recognized. See {@see _retrieveFileClassName()}.
     *  - Errors that happen during this process, such as file read errors,
     *      will cause tests to be skipped, which may result in an empty result set.
     *  - If the file is a valid test case file, the test case will be autoloaded,
     *      if it is not already available. If the test case class cannot be found,
     *      this way an empty set will be returned. No autoloader will be registered
     *      by this class - the consumer of this locator must take care of class
     *      loading.
     *
     * @throws InvalidArgumentException If path is already added.
     *
     * @return AbstractFilePathLocator This instance.
     */
    public function addPath($path)
    {
        if (is_string($path)) {
            $path = $this->_normalizePath($path);
        }

        $key = $this->_hash($path);
        if (isset($this->pathSpecs[$key])) {
            $path = is_string($path)
                    ? sprintf('"%1$s"', $path)
                    : sprintf('with hash "%1$s"', $key);
            throw new InvalidArgumentException(sprintf('Could not add path to locator: path %1$s already added', $path));
        }

        $this->pathSpecs[$key] = $path;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function locate()
    {
        $items = array();
        foreach ($this->_getFilePaths() as $_path) {
            $tests = $this->_getFileTests($_path);
            $items = $this->_arrayMerge($items, $tests);
        }

        $items = $this->_createResultSet($items);

        return $items;
    }

    /**
     * Merges elements from the second array into the first.
     *
     * Necessary primarily because {@see array_merge()} does not work on traversables.
     *
     * @since 0.1.0
     *
     * @param array              $array       The array to merge into.
     * @param array|\Traversable $traversable The second traversable structure.
     *
     * @return array The original array, with elements from the second array added.
     *               Elements with same key will be overwrittenю
     */
    public function _arrayMerge($array, $traversable)
    {
        foreach ($traversable as $_key => $_value) {
            $array[$_key] = $_value;
        }

        return $array;
    }

    /**
     * Create a list of tests for a class contained in the file identified by path.
     *
     * For naming and recognition rules, as well as for how the class file will
     * be treated, see {@see addPath()}.
     *
     * @since 0.1.0
     *
     * @param string $path A full path to the class file.
     *
     * @return Test\TestInterface[]|\Traversable A list of tests.
     */
    protected function _getFileTests($path)
    {
        if (!($className = $this->_retrieveFileClassName($path))) {
            return array();
        }

        $classLocator = $this->_createClassLocator($className);

        return $classLocator->locate();
    }

    /**
     * Get the class name in a class file.
     *
     * This will not lead to the contents of the file to be evaluated,
     * and therefore any classes declared in that file may not be available
     * after this method runs.
     *
     * @since 0.1.0
     *
     * @param string $path The path to a file that may contain a test case.
     *
     * @throws \RuntimeException If the file cannot be read.
     *
     * @return string The fully qualified name of the class, if recognized;
     *                otherwise, null.
     */
    protected function _retrieveFileClassName($path)
    {
        $basename = $this->_basename($path);
        if (!is_readable($path)) {
            throw new \RuntimeException(sprintf('Could not retrieve class name: cannot read file "%1$s"', $path));
        }

        $fileContents = file_get_contents($path, false, null, 0, static::FILE_READ_LIMIT);
        $matches      = array();
        if (!preg_match('!class[\s]+' . preg_quote($basename, '!') . '!', $fileContents, $matches)) {
            return;
        }

        $matches = array();
        if (preg_match('!namespace ([\w\d_\\\]+);!', $fileContents, $matches)) {
            return $matches[1] . '\\' . $basename;
        }

        return $basename;
    }

    /**
     * Create a new class locator for the specified class name.
     *
     * That locator would locate tests in the class.
     *
     * @since 0.1.0
     *
     * @param string $className Name of the class, for which to create the locator.
     *
     * @return ClassLocatorInterface The new class locator.
     */
    abstract protected function _createClassLocator($className);

    /**
     * Get the registered path specifications.
     *
     * @since 0.1.0
     * @see addPath()
     *
     * @return string[]|\Traversible A list of path specifications.
     */
    protected function _getPathsSpecs()
    {
        return $this->pathSpecs;
    }

    /**
     * The eventual array of paths, which this locator will try to load tests from.
     *
     * @since 0.1.0
     * @see addPath()
     *
     * @return string[]|\Traversable A list of unique full paths resolved from the path specs.
     *                               Only paths that pass evaluation by {@see _matchFile()} are contained here.
     */
    protected function _getFilePaths()
    {
        $results = array();
        foreach ($this->_getPathsSpecs() as $_pathExpr) {
            $paths = $this->_resolvePathSpec($_pathExpr);
            foreach ($paths as $_path) {
                if ($this->_matchFile($_path)) {
                    $results[$_path] = true;
                }
            }
        }

        return array_keys($results);
    }

    /**
     * Determine if a file matches the criteria of this locator.
     *
     * @since 0.1.0
     *
     * @return bool True if the file matches this locator's criterie; false otherwise.
     */
    abstract protected function _matchFile($file);

    /**
     * Determine if a string ends with a suffix.
     *
     * @since 0.1.0
     *
     * @param string $string         The string to check.
     * @param string $requiredSuffix The suffix to check for.
     *
     * @return bool True if the specified string ends with the specified suffix;
     *              false otherwise.
     */
    protected function _endsWith($string, $requiredSuffix)
    {
        $requiredLength = strlen($requiredSuffix);
        $suffix         = substr($string, -$requiredLength, $requiredLength);

        return $suffix === $requiredSuffix;
    }

    /**
     * Resolve a path specification to a list of full paths.
     *
     * @see addPath()
     * @since 0.1.0
     *
     * @param string[]|\Traversable $paths The path spec to resolve.
     *
     * @return array|\Traversable A list of full paths for existing files.
     *                            Possibly contains zero, one, or any other number of elements.
     */
    protected function _resolvePathSpec($paths)
    {
        $resolved = array();

        // Strings treaded as file patterns, but existing file paths treated literally
        if (is_string($paths)) {
            $paths = is_file($paths)
                    ? array($paths)
                    : new GlobIterator($paths);
        }

        //  Certain file iteration modes apply
        if ($paths instanceof FilesystemIterator) {
            try {
                $paths->setFlags(
                    FilesystemIterator::SKIP_DOTS |
                    FilesystemIterator::FOLLOW_SYMLINKS |
                    FilesystemIterator::KEY_AS_PATHNAME |
                    FilesystemIterator::CURRENT_AS_SELF
                );
            }
            /* No files found, return empty array
             * https://bugs.php.net/bug.php?id=55701
             */
            catch (\LogicException $lx) {
                return array();
            }
        }

        // Normalize array to iterator
        if (is_array($paths)) {
            $paths = new RecursiveArrayIterator(array_flip($paths));
        }

        // Recursive iteration modes
        if ($paths instanceof RecursiveIterator) {
            $paths = new RecursiveIteratorIterator($paths,
                    RecursiveIteratorIterator::SELF_FIRST |
                    RecursiveIteratorIterator::LEAVES_ONLY);
        }

        // Standardized processing of any traversable
        foreach ($paths as $_path => $_misc) {
            // Guarantees full canonical normalized path
            $path = $this->_normalizePath(realpath($_path));
            if (is_file($path)) { // Only existing paths allowed
                $key            = $this->_hashPath($path);
                $resolved[$key] = $path;
            }
        }

        return $resolved;
    }

    /**
     * Normalizes a path expression.
     *
     * @since 0.1.0
     *
     * @param string $path The path expression to normalize.
     *
     * @return string The normalized expression.
     */
    protected function _normalizePath($path)
    {
        $path = trim($path);
        $path = str_replace('\\/', DIRECTORY_SEPARATOR, $path);

        return rtrim($path, '/');
    }

    /**
     * Create a hash of a filesystem path.
     *
     * @since 0.1.0
     * @see _hash()
     *
     * @param string $path A string representing a filesystem path.
     *
     * @return string The path's hash.
     */
    protected function _hashPath($path)
    {
        return $this->_hash($path);
    }

    /**
     * Gets a basename of a file path, clean of any extensions.
     *
     * @since 0.1.0
     *
     * @param string $fileName File path to get the basename of.
     *
     * @return string The basename without extension.
     */
    protected function _basename($fileName)
    {
        $basename = basename($fileName);
        $basename = explode('.', $basename);

        return isset($basename[0])
                ? $basename[0]
                : $fileName;
    }
}
