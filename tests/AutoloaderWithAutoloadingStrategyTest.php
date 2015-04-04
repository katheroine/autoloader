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
 * AutoloaderWithAutoloadingStrategyTest.
 * PHPUnit test class for Autloader with autloading strategy class.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
abstract class AutoloaderWithAutoloadingStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Instance of tested containing class.
     *
     * @var Autoloader
     */
    private $autoloader;

    /**
     * Instance of tested contained class.
     *
     * @var AutoloadingStrategyInterface
     */
    protected $strategy;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->initialiseAutoloader();
        $this->initialiseStrategy();
        $this->setUpAutoloader();
        $this->registerAutoloader();
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->unregisterAutoloader();
    }

    /**
     * Initialise strategy fixture.
     */
    abstract protected function initialiseStrategy();

    /**
     * Initialise autoloader.
     */
    private function initialiseAutoloader()
    {
        $this->autoloader = new Autoloader();
    }

    /**
     * Set-up autoloader.
     * Configure with the proper autoloading strategy.
     */
    private function setUpAutoloader()
    {
        $this->autoloader->setAutoloadingStrategy($this->strategy);
    }

    /**
     * Register configured autoloading strategy.
     */
    private function registerAutoloader()
    {
        $this->autoloader->register();
    }

    /**
     * Unregister configured autoloading strategy.
     */
    private function unregisterAutoloader()
    {
        $this->autoloader->unregister();
    }

    /**
     * Get full path for given partial path
     * of autoloaded class files.
     *
     * @param string $path
     */
    protected function getFullFixturePath($path)
    {
        $fullClassName = get_called_class();
        $fullClassNameSections = explode('\\', $fullClassName);
        $shortClassName = array_pop($fullClassNameSections);
        $classNameSections = null;
        preg_match_all('/((?:^|[A-Z])[a-z]+)/', $shortClassName, $classNameSections);
        $directoryName = strtolower($classNameSections[0][2]);
        $path = (__DIR__) . '/fixtures/' . $directoryName . $path;

        return $path;
    }
}
