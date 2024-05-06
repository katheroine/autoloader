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
 * Autoloader.
 * Extensible universal autoloader.
 * Provide autoloader mechanism
 * and basic autoloading strategies
 * with possibility to extenting for another standards.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class Autoloader
{
    /**
     * Autoloading strategy.
     *
     * @var AutoloadingStrategyInterface
     */
    private AutoloadingStrategyInterface $autoloadingStrategy;

    /**
     * Set autoloading strategy.
     *
     * @param AutoloadingStrategyInterface $autoloadingStrategy
     */
    public function setAutoloadingStrategy(AutoloadingStrategyInterface $autoloadingStrategy): void
    {
        $this->autoloadingStrategy = $autoloadingStrategy;
    }

    /**
     * Register autoloading function.
     */
    public function register(): void
    {
        spl_autoload_register([$this->autoloadingStrategy, 'loadClass'], true);
    }

    /**
     * Unregister autoloading function.
     */
    public function unregister(): void
    {
        spl_autoload_unregister([$this->autoloadingStrategy, 'loadClass']);
    }
}
