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
 * Psr0AutoloadingStrategy.
 * Autoloading strategy for PSR-0 standard.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class Psr0AutoloadingStrategy extends AbstractAutoloadingStrategy
{
    /**
     * Namespaces with assigned directory path.
     *
     * @var array[string]string | arrya()
     */
    protected $namespacePaths = array();

    /**
     * Namespace of the currently processed class name.
     *
     * @var string | null
     */
    protected $currentNamespace = null;

    /**
     * Partial file path of the currently processed class name.
     *
     * @var string | null
     */
    protected $currentPath = null;

    /**
     * Register namespace and assign a directory path.
     *
     * @param string $namespace
     * @param string $path
     */
    public function registerNamespacePath($namespace, $path)
    {
        $this->namespacePaths[$namespace] = $path;
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
        // removing '\' characters from the beginning of the name
        $classFullName = ltrim($class, '\\');

        // extracting namespace chain and strict class name
        $namespaceEndPosition = strrpos($classFullName, '\\');
        $classNameStartPosition = $namespaceEndPosition + 1;
        $this->currentNamespace = substr($classFullName, 0, $namespaceEndPosition);
        $className = substr($classFullName, $classNameStartPosition);

        // building namespace and class name path components
        $namespacePath = str_replace('\\', DIRECTORY_SEPARATOR, $this->currentNamespace);
        $classPath = str_replace('_', DIRECTORY_SEPARATOR, $className);

        // building class file path
        $this->currentPath = $namespacePath . DIRECTORY_SEPARATOR . $classPath . '.php';
    }

    /**
     * Find full path of the file that contains
     * the declaration of the automatically loaded class.
     *
     * @return string | null
     */
    protected function findClassFilePath()
    {
        foreach ($this->namespacePaths as $namespace => $path) {
            $namespaceIsNotRegistered = (0 !== strpos($this->currentNamespace, $namespace));
            if ($namespaceIsNotRegistered) {
                continue;
            }

            $classFilePath = $path . DIRECTORY_SEPARATOR . $this->currentPath;
            $classFileExists = is_file($classFilePath);

            if ($classFileExists) {
                return $classFilePath;
            }
        }
    }
}
