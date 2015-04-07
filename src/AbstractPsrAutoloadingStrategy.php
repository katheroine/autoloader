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
 * AbstractPsrAutoloadingStrategy.
 * Autoloading strategy for PSR autoloading standards.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
abstract class AbstractPsrAutoloadingStrategy extends AbstractAutoloadingStrategy
{
    /**
     * Namespaces with assigned directory path.
     *
     * @var array[string]string | arrya()
     */
    protected $namespacePaths = array();

    /**
     * Namespace of the currently processed class name.
     *
     * @var string | null
     */
    protected $currentNamespace = null;

    /**
     * Partial file path of the currently processed class name.
     *
     * @var string | null
     */
    protected $currentPath = null;

    /**
     * Register namespace and assign a directory path.
     *
     * @param string $namespace
     * @param string $path
     */
    public function registerNamespacePath($namespace, $path)
    {
        $this->namespacePaths[$namespace] = $path;
    }
}
