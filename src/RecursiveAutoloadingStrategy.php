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

namespace Exorg\Autoloader;

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
     * @var string[] | array[]
     */
    protected array $paths = array();

    /**
     * File name for the currently processed class.
     *
     * @var string
     */
    protected string $processedFile;

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
     * @param string $class
     */
    public function extractClassParameters(string $class): void
    {
        $classStrictName = $this->extractClassStrictName($class);

        $this->processedFile = $classStrictName . '.php';
    }

    /**
     * Find full path of the file that contains
     * the declaration of the automatically loaded class.
     *
     * @return string | null
     */
    protected function findClassFilePath(): ?string
    {
        foreach ($this->paths as $path) {
            $classFilePath = $this->findFileInDirectoryPath($this->processedFile, $path);

            $classFileExists = ! is_null($classFilePath) && is_file($classFilePath);

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
     * @param string $classFullName
     */
    protected static function extractClassStrictName(string $classFullName): string
    {
        // removing '\' characters from the beginning of the name
        $classFullName = ltrim($classFullName, '\\');

        // extracting namespace chain and strict class name
        $namespaceEndPosition = strrpos($classFullName, '\\');

        $namespaceFound = (bool) $namespaceEndPosition;

        if ($namespaceFound) {
            $classNameStartPosition = $namespaceEndPosition + 1;
            $classStrictName = substr($classFullName, $classNameStartPosition);
        } else {
            $classStrictName = $classFullName;
        }

        return $classStrictName;
    }

    /**
     * Find full path of the file in the directory.
     *
     * @param string $fileName
     * @param string $directoryPath
     *
     * @return string | null $filePath
     */
    private function findFileInDirectoryPath(string $fileName, string $directoryPath): ?string
    {
        try {
            $directoryIterator = new \RecursiveDirectoryIterator($directoryPath);
            $directoryItems = new \RecursiveIteratorIterator($directoryIterator);

            foreach ($directoryItems as $directoryItem) {
                $directoryItemIsSearchedFile = ($directoryItem->getFilename() === $fileName);

                if ($directoryItemIsSearchedFile) {
                    return $directoryItem->getPathname();
                }
            }

            return null;
        } catch (\RuntimeException $exception) {
            // Directory path defines empty directory
            return null;
        }
    }
}
