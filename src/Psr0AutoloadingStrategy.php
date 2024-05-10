<?php

declare(strict_types=1);

/*
 * This file is part of the Autoloader package.
 *
 * (c) Katarzyna Krasińska <katheroine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ExOrg\Autoloader;

/**
 * PSR-0 autoloading strategy.
 * Autoloading strategy for PSR-0 standard.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class Psr0AutoloadingStrategy extends AbstractPsrAutoloadingStrategy
{
    /**
     * Extract class paramaters like namespace or class name
     * needed in file searching process
     * and assign their values to the strategy class variables.
     *
     * @param string $class
     */
    protected function extractClassParameters(string $class): void
    {
        // Removing '\' characters from the beginning of the name
        $classFullName = ltrim($class, '\\');

        // Extracting namespace chain and strict class name
        $namespaceEndPosition = strrpos($classFullName, '\\') ?: null;
        $this->processedNamespace = substr($classFullName, 0, $namespaceEndPosition);

        // Building namespace path snippet
        $namespacePath = str_replace('\\', DIRECTORY_SEPARATOR, $this->processedNamespace);

        // Building class path snippet
        $classNameStartPosition = $namespaceEndPosition + 1;
        $className = substr($classFullName, $classNameStartPosition);
        $classPath = str_replace('_', DIRECTORY_SEPARATOR, $className);

        // Building entire class file path
        $this->processedPath = $namespacePath . DIRECTORY_SEPARATOR . $classPath . '.php';
    }

    /**
     * Find full path of the file that contains
     * the declaration of the automatically loaded class.
     *
     * @return string | null
     */
    protected function findClassFilePath(): ?string
    {
        foreach ($this->namespacePaths as $namespace => $paths) {
            foreach ($paths as $path) {
                $namespaceIsNotRegistered = (0 !== strpos($this->processedNamespace, $namespace));

                if ($namespaceIsNotRegistered) {
                    continue;
                }

                $classFilePath = $path . DIRECTORY_SEPARATOR . $this->processedPath;

                $classFileExists = is_file($classFilePath);

                if ($classFileExists) {
                    return $classFilePath;
                }
            }
        }

        return null;
    }
}
