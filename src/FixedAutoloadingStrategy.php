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
 * FixedAutoloadingStrategy.
 * Autoloader strategy for fixed paths.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class FixedAutoloadingStrategy implements AutoloadingStrategyInterface
{
    /**
     * Class full names with assigned directory path.
     *
     * @var array[string]string | array[]
     */
    protected $classPaths = array();

    /**
     * Currently processed class name.
     *
     * @var string | null
     */
    protected $currentClass = null;

    /**
     * Register class full name and assign a directory path.
     *
     * @param string $class
     * @param string $path
     */
    public function registerClassPath($class, $path)
    {
        $this->classPaths[$class] = $path;
    }

    /**
     * Search for the class file path and load it.
     *
     * @param string $class
     * @return boolean
     */
    public function loadClass($class)
    {
        $this->currentClass = $class;

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
     * Find class full file path.
     *
     * @return string | null
     */
    protected function findClassFilePath()
    {
        $classFilePath = null;

        foreach ($this->classPaths as $class => $path) {
            $pathIsNotRegistered = (0 !== strpos(strrev($this->currentClass), strrev($class)));
            if ($pathIsNotRegistered) {
                continue;
            }

            $classFileExists = is_file($path);

            if ($classFileExists) {
                $classFilePath = $path;
            }
        }

        return $classFilePath;
    }
}
