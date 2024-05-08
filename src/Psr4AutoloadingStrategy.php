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
 * PSR-4 autoloading strategy.
 * Autoloading strategy for PSR-4 standard.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
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
    protected function extractClassParameters(string $fullyQualifiedClassName): void
    {
        // Removing '\' characters from the beginning of the name
        $fullClassName = ltrim(string: $fullyQualifiedClassName, characters: '\\');

        // Extracting namespace chain and strict class name
        $namespaceEndPosition = strrpos(haystack: $fullClassName, needle: '\\') /* last ocurrance of '\\' */;
        $this->processedNamespace = substr(string: $fullClassName, offset: 0, length: $namespaceEndPosition);

        // Building namespace path snippet
        $namespacePathSnippet = str_replace(search: '\\', replace: DIRECTORY_SEPARATOR, subject: $this->processedNamespace);

        // Building class path snippets
        $classNameStartPosition = $namespaceEndPosition + 1;
        $classPathSnippet = substr(string: $fullClassName, offset: $classNameStartPosition);

        // Building class file path with namespace prefix
        $this->processedPath = $namespacePathSnippet . DIRECTORY_SEPARATOR . $classPathSnippet . '.php';
    }

    /**
     * Find full path of the file that contains
     * the declaration of the automatically loaded class.
     *
     * @return string | null
     */
    protected function findClassFilePath(): ?string
    {
        foreach ($this->namespacePaths as $registeredNamespace => $registeredPath) {
            $namespaceIsRegistered = $this->processedNamespaceLiesInRegisteredNamespace($registeredNamespace);

            if (! $namespaceIsRegistered) {
                continue;
            }

            $classFilePath = $this->buildClassFilePath($registeredNamespace, $registeredPath);

            $classFileExists = is_file($classFilePath);

            if ($classFileExists) {
                return $classFilePath;
            }
        }

        return null;
    }

    /**
     * Check if the currently processed namespace is the beggining of the given namespace.
     *
     * @param string $registeredNamespace
     *
     * @return bool
     */
    private function processedNamespaceLiesInRegisteredNamespace(string $registeredNamespace): bool
    {
        return (0 == strpos($this->processedNamespace, $registeredNamespace) /* first occurance of $namespace */);
    }

    /**
     * Build a path of the file containing the class to load.
     *
     * @param string $registeredNamespace
     * @param string $registeredPath
     */
    private function buildClassFilePath(string $registeredNamespace, string $registeredPath): string
    {
        // Building namespace path prefix
        $namespacePath = str_replace('\\', DIRECTORY_SEPARATOR, $registeredNamespace);

        // Cutting off namespace path prefix from class file path
        $namespaceCutOffPosition = strlen($namespacePath);
        $classPathSnippet = substr($this->processedPath, $namespaceCutOffPosition);

        // Building class file path without namespace prefix
        $classFilePath = $registeredPath . $classPathSnippet;

        return $classFilePath;
    }
}
