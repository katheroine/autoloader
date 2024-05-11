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
 * PSR autoloading strategy.
 * Autoloading strategy for PSR autoloading standards.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
abstract class AbstractPsrAutoloadingStrategy extends AbstractAutoloadingStrategy
{
    /**
     * Namespaces with assigned directory path.
     *
     * @var string[] | array[]
     */
    protected array $namespacePaths = [];

    /**
     * Actually processed namespaced class name.
     *
     * @var string
     */
    protected string $processedNamespacedClassName = '';

    /**
     * Register namespace and assign a directory path.
     *
     * @param string $namespace
     * @param string $path
     */
    public function registerNamespacePath(string $namespace, string $path): void
    {
        $this->namespacePaths[$namespace][] = $path;
    }
}
