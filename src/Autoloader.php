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
 * Autoloader.
 * Extensible universal autoloader.
 * Provide autoloader mechanism
 * and basic autoloading strategies
 * with possibility to extenting for another standards.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class Autoloader
{
    /**
     * Autoloading strategy.
     *
     * @var AutoloadingStrategy
     */
    private $autoloadingStrategy;

    /**
     * Set autoloading strategy.
     *
     * @param AutoloadingStrategyInterface $autoloadingStrategy
     */
    public function setAutoloadingStrategy(AutoloadingStrategyInterface $autoloadingStrategy)
    {
        $this->autoloadingStrategy = $autoloadingStrategy;
    }

    /**
     * Register autoloading function.
     */
    public function register()
    {
        spl_autoload_register([$this->autoloadingStrategy, 'loadClass'], true);
    }

    /**
     * Unregister autoloading function.
     */
    public function unregister()
    {
        spl_autoload_unregister([$this->autoloadingStrategy, 'loadClass']);
    }
}
