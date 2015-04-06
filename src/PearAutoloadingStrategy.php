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
 * PearAutoloadingStrategy.
 * Autoloading strategy for PEAR-like directory structures.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class PearAutoloadingStrategy implements AutoloadingStrategyInterface
{
    /**
     * Class full names with assigned directory path.
     *
     * @var array[string]string | array[]
     */
    protected $classPaths = array();

    /**
     * Currently processed full name of the class.
     *
     * @var unknown
     */
    protected $currentClass = null;

    /**
     * Partial file path of the currently processed class name.
     *
     * @var string | null
     */
    protected $currentPath = null;

    /**
     * Register prefix and assign a directory path.
     *
     * @param string $prefix
     * @param string $path
     */
    public function registerPrefixPath($prefix, $path)
    {
        $this->prefixPaths[$prefix] = $path;
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
        $this->currentClass = $classFullName;

        $classPath = str_replace('_', DIRECTORY_SEPARATOR, $classFullName);

        $this->currentPath = $classPath . '.php';
    }

    /**
     * Find class full file path.
     *
     * @return string | null
     */
    protected function findClassFilePath()
    {
        foreach ($this->prefixPaths as $prefix => $path) {
            $prefixIsNotRegistered = (0 !== strpos($this->currentClass, $prefix));
            if ($prefixIsNotRegistered) {
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
