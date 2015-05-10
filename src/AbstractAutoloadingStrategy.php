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
 * AbstractAutoloadingStrategy.
 * Autoloading strategy.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
abstract class AbstractAutoloadingStrategy implements AutoloadingStrategyInterface
{
    /**
     * Search for the class file path and load it.
     *
     * @param string $class
     * @return boolean
     */
    public function loadClass($class)
    {
        $this->extractClassParameters($class);

        $classFilePath = $this->findClassFilePath();
        $classFileFound = !is_null($classFilePath);

        if ($classFileFound) {
            require $classFilePath;

            return true;
        } else {
            return false;
        }
    }

    /**
     * Extract class paramaters like namespace or class name
     * needed in file searching process
     * and assign their values to the strategy class variables.
     *
     * @param string $class
     */
    abstract protected function extractClassParameters($class);

    /**
     * Find full path of the file that contains
     * the declaration of the automatically loaded class.
     *
     * @return string | null
     */
    abstract protected function findClassFilePath();
}
