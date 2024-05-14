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
     * assigned to the class name prefixes (pseudo-namespaces).
     *
     * @var array
     */
    protected array $pseudonamespacePaths = [];

    /**
     * Actually processed prefixed (pseudo-namespaced) class name.
     *
     * @var string
     */
    protected string $processedPseudonamespacedClassName = '';

    /**
     * Register pseudo-namespace and assign a directory path.
     *
     * @param string $pseudonamespace
     * @param string $path
     */
    public function registerPseudonamespacePath(string $pseudonamespace, string $path): void
    {
        $this->pseudonamespacePaths[$pseudonamespace][] = $path;
    }

    /**
     * Extract class paramaters like namespace or class name
     * needed in file searching process
     * and assign their values to the strategy class variables.
     *
     * @param string $pseudonamespacedClassName
     */
    protected function extractClassParameters(string $pseudonamespacedClassName): void
    {
        $this->processedPseudonamespacedClassName = $pseudonamespacedClassName;
    }

    /**
     * Find full path of the file that contains
     * the declaration of the automatically loaded class.
     *
     * @return string | null
     */
    protected function findClassFilePath(): ?string
    {
        foreach ($this->pseudonamespacePaths as $registeredPseudonamespacePrefix => $registeredBaseDirPaths) {
            foreach ($registeredBaseDirPaths as $registeredBaseDirPath) {
                if (! $this->processedPseudonamespacedClassNameContainsPrefix($registeredPseudonamespacePrefix)) {
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

    private function processedPseudonamespacedClassNameContainsPrefix(string $prefix): bool
    {
        $pseudonamespacePrefix = substr(
            string: $this->processedPseudonamespacedClassName,
            offset: 0,
            length: strlen($prefix)
        );
        $processedPseudonamespaceHasReisteredPrefix = ($prefix == $pseudonamespacePrefix);

        return $processedPseudonamespaceHasReisteredPrefix;
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
        // Building pseudo-namespaced class path snippet
        $prefixedClassPath = str_replace(
            search: '_',
            replace: DIRECTORY_SEPARATOR,
            subject: $this->processedPseudonamespacedClassName
        );

        // Building entire class file path
        $classFilePathWithinBaseDir = $prefixedClassPath . '.php';

        return $classFilePathWithinBaseDir;
    }
}
