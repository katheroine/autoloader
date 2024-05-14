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
 * Recursive autoloading strategy.
 * Autoloading strategy for recursve directory searching.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class RecursiveAutoloadingStrategy extends AbstractAutoloadingStrategy
{
    /**
     * Directory paths where class files are searched.
     *
     * @var string[]
     */
    protected array $paths = [];

    /**
     * File name (unnamespaced & with extension) of the currently processed class.
     *
     * @var string
     */
    protected string $processedClassFileName;

    /**
     * Register path for recursive search by autoloader.
     *
     * @param string $path
     */
    public function registerPath(string $path): void
    {
        $this->paths[] = $path;
    }

    /**
     * Extract class paramaters like namespace or class name
     * needed in file searching process
     * and assign their values to the strategy class variables.
     *
     * @param string $fullyQualifiedClassName
     */
    public function extractClassParameters(string $fullyQualifiedClassName): void
    {
        // Removing '\' characters from the beginning of the fully qualified class name
        $processedClassName = ltrim(
            string: $fullyQualifiedClassName,
            characters: '\\'
        );

        $this->processedClassFileName = $this->extractUnnamespacedClassName($processedClassName) . '.php';
    }

    /**
     * Find full path of the file that contains
     * the declaration of the automatically loaded class.
     *
     * @return string | null
     */
    protected function findClassFilePath(): ?string
    {
        foreach ($this->paths as $registeredBaseDirPath) {
            $classFilePath = $this->findProcessedFileInDirectoryPath($registeredBaseDirPath);

            $classFileExists = is_file($classFilePath);

            if ($classFileExists) {
                return $classFilePath;
            }
        }

        return null;
    }

    /**
     * Decompose class full name.
     * Strip namespace and extract class strict name.
     *
     * @param string $className
     *
     * @return string
     */
    protected static function extractUnnamespacedClassName(string $className): string
    {
        // Extracting class namespace
        $namespaceEndPosition = strrpos(
            haystack: $className,
            needle: '\\'
        );

        $namespaceFound = ($namespaceEndPosition !== false);

        if ($namespaceFound) {
            $classNameStartPosition = $namespaceEndPosition + 1;
            $unnamespacedClassName = substr(
                string: $className,
                offset: $classNameStartPosition
            );
        } else {
            $unnamespacedClassName = $className;
        }

        return $unnamespacedClassName;
    }

    /**
     * Find full path of the file in the directory.
     *
     * @param string $directoryPath
     *
     * @return string
     */
    private function findProcessedFileInDirectoryPath(string $directoryPath): string
    {
        try {
            $directoryIterator = new \RecursiveDirectoryIterator($directoryPath);
            $directoryItems = new \RecursiveIteratorIterator($directoryIterator);

            foreach ($directoryItems as $directoryItem) {
                $directoryItemIsSearchedFile = ($directoryItem->getFilename() === $this->processedClassFileName);

                if ($directoryItemIsSearchedFile) {
                    return $directoryItem->getPathname();
                }
            }

            return '';
        } catch (\RuntimeException $exception) {
            // Directory path defines empty directory
            return '';
        }
    }
}
