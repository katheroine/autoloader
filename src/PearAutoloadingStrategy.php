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
     * Actually processed prefixed class name.
     *
     * @var string
     */
    protected string $processedPrefixedClassName = '';

    /**
     * Register prefix and assign a directory path.
     *
     * @param string $prefix
     * @param string $path
     */
    public function registerPrefixPath(string $prefix, string $path): void
    {
        $this->prefixPaths[$prefix][] = $path;
    }

    /**
     * Extract class paramaters like namespace or class name
     * needed in file searching process
     * and assign their values to the strategy class variables.
     *
     * @param string $prefixedClassName
     */
    protected function extractClassParameters(string $prefixedClassName): void
    {
        $this->processedPrefixedClassName = $prefixedClassName;
    }

    /**
     * Find full path of the file that contains
     * the declaration of the automatically loaded class.
     *
     * @return string | null
     */
    protected function findClassFilePath(): ?string
    {
        foreach ($this->prefixPaths as $registeredPrefix => $registeredBaseDirPaths) {
            foreach ($registeredBaseDirPaths as $registeredBaseDirPath) {
                if (! $this->processedPrefixedClassNameContainsPrefix($registeredPrefix)) {
                    continue;
                }

                $classFilePath = $this->buildClassFilePath($registeredBaseDirPath);

                $classFileExists = is_file($classFilePath);

                if ($classFileExists) {
                    return $classFilePath;
                }
            }
        }

        return null;
    }

    private function processedPrefixedClassNameContainsPrefix(string $prefix): bool
    {
        $pseudonamespacePrefix = substr(
            string: $this->processedPrefixedClassName,
            offset: 0,
            length: strlen($prefix)
        );
        $processedPrefixHasReisteredPrefix = ($prefix == $pseudonamespacePrefix);

        return $processedPrefixHasReisteredPrefix;
    }

    private function buildClassFilePath(string $baseDirPath): string
    {
        $classFilePath = $baseDirPath
            . DIRECTORY_SEPARATOR
            . $this->buildClassFilePathWithinBaseDir();

        return $classFilePath;
    }

    protected function buildClassFilePathWithinBaseDir(): string
    {
        // Building pseudonamespaced class path snippet
        $prefixedClassPath = str_replace(
            search: '_',
            replace: DIRECTORY_SEPARATOR,
            subject: $this->processedPrefixedClassName
        );

        // Building entire class file path
        $classFilePathWithinBaseDir = $prefixedClassPath . '.php';

        return $classFilePathWithinBaseDir;
    }
}
