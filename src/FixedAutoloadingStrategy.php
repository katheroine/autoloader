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
     * @var string
     */
    protected $processedClassName = '';

    /**
     * Register class full name and assign a directory path.
     *
     * @param string $class can be namespaced or not
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
     * @param string $className can be namespaced or not
     */
    public function extractClassParameters(string $className): void
    {
         // Removing '\' characters from the beginning of the class name
        $this->processedClassName = ltrim(
            string: $className,
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
        foreach ($this->classPaths as $registeredClassName => $registeredClassFilePath) {
            if ($this->processedClassName != $registeredClassName) {
                continue;
            }

            $classFileExists = is_file($registeredClassFilePath);

            if ($classFileExists) {
                return $registeredClassFilePath;
            }
        }

        return null;
    }
}
