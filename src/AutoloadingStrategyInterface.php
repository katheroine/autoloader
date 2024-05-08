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
 * Autoloading strategy interface.
 * Defines interface of particular autoloading strategy.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
interface AutoloadingStrategyInterface
{
    /**
     * Load proper file containing needed definition.
     *
     * @param string $fullyQualifiedClassName
     *
     * @return boolean
     */
    public function loadClass(string $fullyQualifiedClassName): bool;
}
