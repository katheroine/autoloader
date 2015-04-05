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
 * Autoloader strategy for recursve directory searching.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class RecursiveAutoloadingStrategy implements AutoloadingStrategyInterface
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
    protected $currentFile;

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
     * Search for the class file path and load it.
     *
     * @param string $class
     * @return boolean
     */
    public function loadClass($class)
    {
        $this->setUpClassParametersFromFullName($class);

        $classFilePath = $this->findClassFilePath();
        $classFileFound = !is_null($classFilePath);

        if ($classFileFound) {
            require $classFilePath;
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Set-up class file path from class full name.
     *
     * @param string $classFullName
     */
    protected function setUpClassParametersFromFullName($classFullName)
    {
        $classStrictName = $this->extractClassStrictName($classFullName);

        $this->currentFile = $classStrictName . '.php';
    }

    /**
     * Find class full file path.
     *
     * @return string | null
     */
    protected function findClassFilePath()
    {
        $classFilePath = null;

        foreach ($this->paths as $path) {
            $classFilePath = $this->findFileInDirectoryPath($this->currentFile, $path);
            $classFileExists = is_file($classFilePath);

            if ($classFileExists) {
                break;
            }
        }

        return $classFilePath;
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
            $namespace = substr($classFullName, 0, $namespaceEndPosition); // probably never used and unneeded
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
        $directoryItemsExist = !empty($directoryItems);

        if (!$directoryItemsExist) {
            return null;
        }

        foreach ($directoryItems as $directoryItem) {
            $directoryItemPath = $directoryPath . '/' . $directoryItem;
            $directoryItemIsDirectory = is_dir($directoryItemPath);

            if ($directoryItemIsDirectory) {
                $filePath = $this->findFileInDirectoryPath($fileName, $directoryItemPath);
                $filePathIsFound = !is_null($filePath);

                if ($filePathIsFound) {
                    return $filePath;
                }
            } else {
                $directoryItemIsSearchedFile = ($directoryItem === $fileName);

                if ($directoryItemIsSearchedFile) {
                    return $directoryItemPath;
                }
            }
        }

        return null;
    }

    /**
     * Extract content of the directory.
     *
     * @param string $directoryPath
     * @return mixed:
     */
    private function extractDirectoryItems($directoryPath)
    {
        $directoryItems = scandir($directoryPath);
        $this->removeDotPaths($directoryItems);

        return $directoryItems;
    }

    /**
     * Remove dot paths from the set of directory items.
     *
     * @param array of string $directoryContent
     */
    private function removeDotPaths(&$directoryContent)
    {
        $dotpaths = ['.', '..'];
        $directoryContent = array_diff($directoryContent, $dotpaths);
    }
}
