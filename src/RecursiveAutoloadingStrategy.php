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
 * RecursiveAutoloadingStrategy.
 * Autoloading strategy for recursve directory searching.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class RecursiveAutoloadingStrategy extends AbstractAutoloadingStrategy
{
    /**
     * Directory path where class files are searched.
     *
     * @var array[string] | array[]
     */
    protected $paths = array();

    /**
     * File name for the currently processed class.
     *
     * @var string
     */
    protected $processedFile;

    /**
     * Register path for recursive search by autoloader.
     *
     * @param string $path
     */
    public function registerPath($path)
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
    public function extractClassParameters($class)
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
    protected function findClassFilePath()
    {
        foreach ($this->paths as $path) {
            $classFilePath = $this->findFileInDirectoryPath($this->processedFile, $path);

            $classFileExists = is_file($classFilePath);

            if ($classFileExists) {
                return $classFilePath;
            }
        }
    }

    /**
     * Decompose class full name.
     * Strip namespace and extract class strict name.
     *
     * @param string $classFullName
     */
    protected static function extractClassStrictName($classFullName)
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
     * @return string $filePath
     */
    private function findFileInDirectoryPath($fileName, $directoryPath)
    {
        $directoryItems = self::extractDirectoryItems($directoryPath);

        $directoryItemsExist = !is_null($directoryItems) && !empty($directoryItems);

        if (!$directoryItemsExist) {
            return null;
        }

        foreach ($directoryItems as $directoryItem) {
            $directoryItemPath = $directoryPath . '/' . $directoryItem;

            $directoryItemIsDirectory = $this->pathIsNonemptyDirectory($directoryItemPath);

            if ($directoryItemIsDirectory) {
                return $this->findFileInDirectoryPath($fileName, $directoryItemPath);
            } else {
                $directoryItemIsSearchedFile = ($directoryItem === $fileName);

                if ($directoryItemIsSearchedFile) {
                    return $directoryItemPath;
                }
            }
        }
    }

    /**
     * Extract content of the directory.
     *
     * @param string $directoryPath
     * @return array[string] | null:
     */
    private function extractDirectoryItems($directoryPath)
    {
        $directoryPathExists = is_dir($directoryPath);

        if ($directoryPathExists) {
            $directoryItems = scandir($directoryPath);
            $this->removeDotPaths($directoryItems);

            return $directoryItems;
        }
    }

    /**
     * Check if path is nonempty directory path.
     *
     * @param unknown $path
     * @return boolean
     */
    private function pathIsNonemptyDirectory($path)
    {
        $directoryItems = $this->extractDirectoryItems($path);

        $pathIsNonemptyDirectory = !is_null($directoryItems) && !empty($directoryItems);

        return $pathIsNonemptyDirectory;
    }

    /**
     * Remove dot paths from the set of directory items.
     *
     * @param array of string $directoryContent
     */
    private function removeDotPaths(&$directoryContent)
    {
        $dotpaths = ['.', '..', '.gitignore'];
        $directoryContent = array_diff($directoryContent, $dotpaths);
    }
}
