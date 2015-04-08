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
 * Psr4AutoloadingStrategy.
 * Autoloading strategy for PSR-4 standard.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class Psr4AutoloadingStrategy extends AbstractPsrAutoloadingStrategy
{
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
        $this->processedNamespace = substr($classFullName, 0, $namespaceEndPosition);
        $className = substr($classFullName, $classNameStartPosition);

        // building namespace and class name path components
        $namespacePath = str_replace('\\', DIRECTORY_SEPARATOR, $this->processedNamespace);

        // building class file path
        $this->processedPath = $namespacePath . DIRECTORY_SEPARATOR . $className . '.php';
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
            $namespaceIsNotRegistered = (0 !== strpos($this->processedNamespace, $namespace));
            if ($namespaceIsNotRegistered) {
                continue;
            }

            $namespacePath = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
            $cutOffPosition = strlen($namespacePath);
            $currentPath = substr($this->processedPath, $cutOffPosition);
            $classFilePath = $path . $currentPath;
            $classFileExists = is_file($classFilePath);

            if ($classFileExists) {
                return $classFilePath;
            }
        }
    }
}
