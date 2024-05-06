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
 * Fixed autoloading strategy.
 * Autoloading strategy for fixed paths.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class FixedAutoloadingStrategy extends AbstractAutoloadingStrategy
{
    /**
     * Class full names with assigned directory path.
     *
     * @var string[] | array[]
     */
    protected $classPaths = [];

    /**
     * Currently processed class name.
     *
     * @var string | null
     */
    protected $processedClass = null;

    /**
     * Register class full name and assign a directory path.
     *
     * @param string $class
     * @param string $path
     */
    public function registerClassPath(string $class, string $path): void
    {
        $this->classPaths[$class] = $path;
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
        $this->processedClass = $class;
    }

    /**
     * Find full path of the file that contains
     * the declaration of the automatically loaded class.
     *
     * @return string | null
     */
    protected function findClassFilePath(): ?string
    {
        foreach ($this->classPaths as $class => $path) {
            $pathIsNotRegistered = (0 !== strpos(strrev($this->processedClass), strrev($class)));

            if ($pathIsNotRegistered) {
                continue;
            }

            $classFileExists = is_file($path);

            if ($classFileExists) {
                return $path;
            }
        }

        return null;
    }
}
