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
 * Autoloading strategy test.
 * PHPUnit test class for autoloading strategy classes.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
abstract class AbstractAutoloadingStrategyTestCase extends AutoloadingProcessTestCase
{
    /**
     * Instance of tested class.
     *
     * @var AutoloadingStrategyInterface
     */
    protected AutoloadingStrategyInterface $strategy;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->initialiseStrategy();
        $this->registerAutoloadingStrategy();
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
        $this->unregisterAutoloadingStrategy();
    }

    /**
     * Register proper method of autoloading strategy
     * as an autoloader function.
     */
    protected function registerAutoloadingStrategy(): void
    {
        spl_autoload_register([$this->strategy, 'loadClass'], true);
    }

    /**
     * Unregister proper method of autoloading strategy
     * as an autoloader function.
     */
    protected function unregisterAutoloadingStrategy(): void
    {
        spl_autoload_unregister([$this->strategy, 'loadClass']);
    }

    /**
     * Get full path for given partial path
     * of autoloaded class files.
     *
     * @param string $path
     *
     * @return string
     */
    protected function getFullFixturePath(string $path): string
    {
        $fullClassName = get_called_class();
        $fullClassNameSections = explode('\\', $fullClassName);
        $shortClassName = array_pop($fullClassNameSections);
        $classNameSections = null;
        preg_match_all('/((?:^|[A-Z])[a-z0-9]+)/', $shortClassName, $classNameSections);
        $directoryName = strtolower($classNameSections[0][0]);
        $path = (__DIR__) . '/fixtures/' . $directoryName . $path;

        return $path;
    }

    /**
     * Assert object class implements given interface.
     *
     * @param mixed  $object
     * @param string $interface
     */
    public function assertImplements(mixed $object, string $interface): void
    {
        $implementedInterfaces = class_implements($object);
        $implementsInterface = in_array($interface, $implementedInterfaces);

        $this->assertTrue($implementsInterface);
    }
}
