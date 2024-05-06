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
 * PEAR autoloading strategy.
 * Autoloading strategy for PEAR-like directory structures.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class PearAutoloadingStrategy extends AbstractAutoloadingStrategy
{
    /**
     * Directory paths where class files are searched
     * assigned to the class name prefixes.
     *
     * @var array
     */
    protected array $prefixPaths = [];

    /**
     * Class full names with assigned directory path.
     *
     * @var string[] | array[]
     */
    protected $classPaths = [];

    /**
     * Currently processed full name of the class.
     *
     * @var mixed
     */
    protected $processedClass = null;

    /**
     * Partial file path of the currently processed class name.
     *
     * @var string | null
     */
    protected $processedPath = null;

    /**
     * Register prefix and assign a directory path.
     *
     * @param string $prefix
     * @param string $path
     */
    public function registerPrefixPath(string $prefix, string $path): void
    {
        $this->prefixPaths[$prefix] = $path;
    }

    /**
     * Extract class paramaters like namespace or class name
     * needed in file searching process
     * and assign their values to the strategy class variables.
     *
     * @param string $class
     */
    protected function extractClassParameters(string $class): void
    {
        $this->processedClass = $class;

        $classPath = $this->buildClassPathFromClass($class);

        $this->processedPath = $classPath . '.php';
    }

    /**
     * Find full path of the file that contains
     * the declaration of the automatically loaded class.
     *
     * @return string | null
     */
    protected function findClassFilePath(): ?string
    {
        foreach ($this->prefixPaths as $prefix => $path) {
            $prefixIsNotRegistered = (0 !== strpos($this->processedClass, $prefix));

            if ($prefixIsNotRegistered) {
                continue;
            }

            $classFilePath = $path . DIRECTORY_SEPARATOR . $this->processedPath;

            $classFileExists = is_file($classFilePath);

            if ($classFileExists) {
                return $classFilePath;
            }
        }

        return null;
    }

    /**
     * Build class path from class name.
     *
     * @param string $class
     *
     * @return string
     */
    protected static function buildClassPathFromClass(string $class): string
    {
        $classPath = str_replace('_', DIRECTORY_SEPARATOR, $class);

        return $classPath;
    }
}
