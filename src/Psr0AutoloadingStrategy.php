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
     * @param string $fullyQualifiedClassName
     */
    protected function extractClassParameters(string $fullyQualifiedClassName): void
    {
        // Removing '\' characters from the beginning of the fully qualified class name
        $this->processedNamespacedClassName = ltrim(
            string: $fullyQualifiedClassName,
            characters: '\\'
        );
    }

    /**
     * Find full path of the file that contains
     * the declaration of the automatically loaded class.
     *
     * @return string | null
     */
    protected function findClassFilePath(): ?string
    {
        foreach ($this->namespacePaths as $registeredNamespacePrefix => $registeredBaseDirPaths) {
            foreach ($registeredBaseDirPaths as $registeredBaseDirPath) {
                if (! $this->processedNamespacedClassNameContainsPrefix($registeredNamespacePrefix)) {
                    continue;
                }

                $classFilePath = $this->buildClassFilePath($registeredBaseDirPath);

                $classFileExists = is_file($classFilePath);

                if ($classFileExists) {
                    return $classFilePath;
                }
            }
        }

        return null;
    }

    private function processedNamespacedClassNameContainsPrefix(string $prefix): bool
    {
        $namespacePrefix = substr(
            string: $this->processedNamespacedClassName,
            offset: 0,
            length: strlen($prefix)
        );
        $processedNamespaceHasReisteredPrefix = ($prefix == $namespacePrefix);

        return $processedNamespaceHasReisteredPrefix;
    }

    private function buildClassFilePath(string $baseDirPath): string
    {
        $classFilePath = $baseDirPath
            . DIRECTORY_SEPARATOR
            . $this->buildClassFilePathWithinBaseDir();

        return $classFilePath;
    }

    private function buildClassFilePathWithinBaseDir(): string
    {
        list($classNamespace, $className) = $this->extractProcessedClassNamespaceAndName();

        // Building namespace path snippet
        $namespacePath = str_replace(
            search: '\\',
            replace: DIRECTORY_SEPARATOR,
            subject: $classNamespace
        );

        // Building class path snippet
        $classPath = str_replace(
            search: '_',
            replace: DIRECTORY_SEPARATOR,
            subject: $className
        );

        // Building entire class file path
        $classFilePathWithinBaseDir = $namespacePath
            . DIRECTORY_SEPARATOR
            . $classPath
            . '.php';

        return $classFilePathWithinBaseDir;
    }

    private function extractProcessedClassNamespaceAndName(): array
    {
        // Extracting class namespace
        $namespaceEndPosition = strrpos(
            haystack: $this->processedNamespacedClassName,
            needle: '\\'
        );
        $classNamespace = substr(
            string: $this->processedNamespacedClassName,
            offset: 0,
            length: $namespaceEndPosition
        );

        // Extracting class name
        $classNameStartPosition = $namespaceEndPosition + 1;
        $className = substr(
            string: $this->processedNamespacedClassName,
            offset: $classNameStartPosition
        );

        return [$classNamespace, $className];
    }
}
