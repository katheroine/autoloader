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
     * Actually processed namespaced class name.
     *
     * @var string
     */
    private string $processedNamespacedClassName = '';

    /**
     * Extract class paramaters like namespace or class name
     * needed in file searching process
     * and assign their values to the strategy class variables.
     *
     * @param string $class
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

                $unprefixedNamespacedClassName = $this->unprefixProcessedNamespacedClassName($registeredNamespacePrefix);

                $classFilePath = $this->buildClassFilePath($registeredBaseDirPath, $unprefixedNamespacedClassName);

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

    private function unprefixProcessedNamespacedClassName(string $prefix): string
    {
        $unprefixedNamespacedClassName = substr(
            string: $this->processedNamespacedClassName,
            offset: strlen($prefix)
        );

        return $unprefixedNamespacedClassName;
    }

    private function buildClassFilePath(string $baseDirPath, string $namespacedClassName): string
    {
        $classFilePathWithinBaseDir = ltrim(
            string: str_replace(
                search: '\\',
                replace: DIRECTORY_SEPARATOR,
                subject: $namespacedClassName
            ),
            characters: '/'
        )
        . '.php';

        $classFilePath = $baseDirPath
            . DIRECTORY_SEPARATOR
            . $classFilePathWithinBaseDir;

        return $classFilePath;
    }
}
