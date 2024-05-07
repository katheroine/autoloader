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

use PHPUnit\Framework\TestCase;

/**
 * Autoloader test.
 * PHPUnit test class for Autoloader class.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class AutoloaderTest extends TestCase
{
    /**
     * @var string
     */
    protected const AUTOLOADER_FULLY_QUALIFIED_CLASS_NAME = 'ExOrg\\Autoloader\\Autoloader';
    protected const AUTOLOADING_STRATEGY_INTERFACE_NAME = 'AutoloadingStrategyInterface';
    protected const AUTOLOADING_STRATEGY_FULLY_QUALIFIED_INTERFACE_NAME = 'ExOrg\\Autoloader\\'
        . self::AUTOLOADING_STRATEGY_INTERFACE_NAME ;
    protected const AUTOLOADER_FUNCTION_NAME = 'loadClass';
    protected const TYPE_ERROR_EXCEPTION_CLASS_NAME = 'TypeError';

    /**
     * Instance of tested class.
     *
     * @var Autoloader
     */
    private Autoloader $autoloader;

    /**
     * Autoloading strategy mock.
     * Mocked AutoloadingStrategyInterface.
     *
     * @var mixed
     */
    private mixed $autoloadingStrategyMock;

    /**
     * Test ExOrg\Autoloader\Autoloader class exists.
     */
    public function testConstructorReturnsProperInstance()
    {
        $this->assertInstanceOf(self::AUTOLOADER_FULLY_QUALIFIED_CLASS_NAME, $this->autoloader);
    }

    /**
     * Test setAutoloadingStrategy method
     * doesn't accept argument of class
     * that does not implement ExOrg\Autoloader\IntrfaceAutoloadingStrategy iterface.
     */
    public function testSetAutoloadingStrategyDoesNotAcceptUnproperArgument()
    {
        $this->expectException(self::TYPE_ERROR_EXCEPTION_CLASS_NAME);

        $this->autoloader->setAutoloadingStrategy(new \stdClass());
    }

    /**
     * Test register method registers autoloader class and method properly.
     */
    public function testRegisterRegistersAutoloaderProperly()
    {
        $this->setUpAutoloaderWithStrategy();

        $this->autoloader->register();

        $this->assertAutoloaderRegistered();

        $this->unregisterAutoloaderStrategyMock();
    }

    /**
     * Test unregister method unregisters autoloader class and method properly.
     */
    public function testUnregisterRegistersAutoloaderProperly()
    {
        $this->setUpAutoloaderWithStrategy();
        $this->registerAutoloaderStrategyMock();

        $this->autoloader->unregister();

        $this->assertAutoloaderUnregistered();
    }

        /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->initialiseAutoloader();
        $this->initialiseAutoloadingStrategyMock();
    }

    /**
     * Initialise autoloader fixture.
     */
    protected function initialiseAutoloader(): void
    {
        $this->autoloader = new Autoloader();
    }

    /**
     * Initialise autoloading strategy mock.
     */
    protected function initialiseAutoloadingStrategyMock(): void
    {
        $this->autoloadingStrategyMock = $this->createMock(self::AUTOLOADING_STRATEGY_FULLY_QUALIFIED_INTERFACE_NAME);
    }

    /**
     * Set-up autoloader with autoloading strategy mock.
     */
    protected function setUpAutoloaderWithStrategy(): void
    {
        $this->autoloader->setAutoloadingStrategy($this->autoloadingStrategyMock);
    }

    /**
     * Register proper method of autoloader strategy mock
     * as an autoloader function.
     */
    protected function registerAutoloaderStrategyMock(): void
    {
        spl_autoload_register([$this->autoloadingStrategyMock, self::AUTOLOADER_FUNCTION_NAME], true);
    }

    /**
     * Unregister proper method of autoloader strategy mock
     * as an autoloader function.
     */
    protected function unregisterAutoloaderStrategyMock(): void
    {
        spl_autoload_unregister([$this->autoloadingStrategyMock, self::AUTOLOADER_FUNCTION_NAME]);
    }

    /**
     * Assert autoloader strategy stub
     * has been registered properly.
     */
    protected function assertAutoloaderRegistered(): void
    {
        $this->assertTrue($this->registrationIsCorrect());
    }

    /**
     * Assert autoloader strategy stub
     * is not registered.
     */
    protected function assertAutoloaderUnregistered(): void
    {
        $this->assertFalse($this->registrationIsCorrect());
    }

    /**
     * Indicate that autoloader strategy stub
     * has been registred properly.
     *
     * @return boolean
     */
    private function registrationIsCorrect(): bool
    {
        $autoloadFunctions = spl_autoload_functions();
        $lastRegisteredAutoloader = array_pop($autoloadFunctions);
        $autoloaderClass = $this->getMockedClass($lastRegisteredAutoloader[0]);
        $autoloaderMethod = $lastRegisteredAutoloader[1];

        $classIsCorrect = ($autoloaderClass === self::AUTOLOADING_STRATEGY_INTERFACE_NAME);
        $methodIsCorrect = ($autoloaderMethod === self::AUTOLOADER_FUNCTION_NAME);
        $registrationIsCorrect = $classIsCorrect && $methodIsCorrect;

        return $registrationIsCorrect;
    }

    /**
     * Extract mocked class name
     * from the mock object.
     *
     * @param mixed $mock
     *
     * @return string
     */
    protected function getMockedClass($mock): mixed
    {
        $mockClass = get_class($mock);
        $mockedClass = (substr(substr($mockClass, 11), 0, -9));

        return $mockedClass;
    }
}
