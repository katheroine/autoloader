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
 * Autoloading strategy.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
abstract class AbstractAutoloadingStrategy implements AutoloadingStrategyInterface
{
    /**
     * Search for the class file path and load it.
     *
     * @param string $class
     *
     * @return boolean
     */
    public function loadClass(string $class): bool
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
     * needed in the class file searching process
     * and assign their values to the strategy class variables
     * that will be used by the findClassFilePath method.
     *
     * @param string $class
     */
    abstract protected function extractClassParameters(string $class): void;

    /**
     * Find full path of the file that contains
     * the declaration of the automatically loaded class.
     *
     * @return string | null
     */
    abstract protected function findClassFilePath(): ?string;
}
