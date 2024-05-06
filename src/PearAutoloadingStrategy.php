<?php

/*
 * This file is part of the Autoloader package.
 *
 * (c) Katarzyna Krasińska <katheroine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exorg\Autoloader;

/**
 * PearAutoloadingStrategy.
 * Autoloading strategy for PEAR-like directory structures.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class PearAutoloadingStrategy extends AbstractAutoloadingStrategy
{
    /**
     * Class full names with assigned directory path.
     *
     * @var array[string]string | array[]
     */
    protected $classPaths = array();

    /**
     * Currently processed full name of the class.
     *
     * @var unknown
     */
    protected $processedClass = null;

    /**
     * Partial file path of the currently processed class name.
     *
     * @var string | null
     */
    protected $processedPath = null;

    protected $prefixPaths = array();

    /**
     * Register prefix and assign a directory path.
     *
     * @param string $prefix
     * @param string $path
     */
    public function registerPrefixPath($prefix, $path)
    {
        $this->prefixPaths[$prefix] = $path;
    }

    /**
     * Extract class paramaters like namespace or class name
     * needed in file searching process
     * and assign their values to the strategy class variables.
     *
     * @param string $class
     */
    protected function extractClassParameters($class)
    {
        $this->processedClass = $class;

        $classPath = $this->buildClassPathFromClass($class);

        $this->processedPath = $classPath . '.php';
    }

    /**
     * Find full path of the file that contains
     * the declaration of the automatically loaded class.
     *
     * @return string | null
     */
    protected function findClassFilePath()
    {
        foreach ($this->prefixPaths as $prefix => $path) {
            $prefixIsNotRegistered = (0 !== strpos($this->processedClass, $prefix));

            if ($prefixIsNotRegistered) {
                continue;
            }

            $classFilePath = $path . DIRECTORY_SEPARATOR . $this->processedPath;

            $classFileExists = is_file($classFilePath);

            if ($classFileExists) {
                return $classFilePath;
            }
        }
    }

    /**
     * Build class path from class name.
     *
     * @param string $class
     * @return string
     */
    protected static function buildClassPathFromClass($class)
    {
        $classPath = str_replace('_', DIRECTORY_SEPARATOR, $class);

        return $classPath;
    }
}
