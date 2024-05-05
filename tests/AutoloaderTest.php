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

use PHPUnit\Framework\TestCase;

/**
 * AutoloaderTest.
 * PHPUnit test class for Autoloader class.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class AutoloaderTest extends TestCase
{
    /**
     * Instance of tested class.
     *
     * @var Autoloader
     */
    private $autoloader;

    /**
     * Autoloading strategy mock.
     * Mocked AutoloadingStrategyInterface.
     *
     * @var mixed
     */
    private $autoloadingStrategyMock;

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
    protected function initialiseAutoloader()
    {
        $this->autoloader = new Autoloader();
    }

    /**
     * Initialise autoloading strategy mock.
     */
    protected function initialiseAutoloadingStrategyMock()
    {
        $this->autoloadingStrategyMock = $this->getMockBuilder('Exorg\Autoloader\AutoloadingStrategyInterface')
            ->getMock();
    }

    /**
     * Set-up autoloader with autoloading strategy mock.
     */
    protected function setUpAutoloaderWithStrategy()
    {
        $this->autoloader->setAutoloadingStrategy($this->autoloadingStrategyMock);
    }

    /**
     * Register proper method of autoloader strategy mock
     * as an autoloader function.
     */
    protected function registerAutoloaderStrategyMock()
    {
        spl_autoload_register([$this->autoloadingStrategyMock, 'loadClass'], true);
    }

    /**
     * Unregister proper method of autoloader strategy mock
     * as an autoloader function.
     */
    protected function unregisterAutoloaderStrategyMock()
    {
        spl_autoload_unregister([$this->autoloadingStrategyMock, 'loadClass']);
    }

    /**
     * Assert autoloader strategy stub
     * has been registered properly.
     */
    protected function assertAutoloaderRegistered()
    {
        $this->assertTrue($this->registrationIsCorrect());
    }

    /**
     * Assert autoloader strategy stub
     * is not registered.
     */
    protected function assertAutoloaderUnregistered()
    {
        $this->assertFalse($this->registrationIsCorrect());
    }

    /**
     * Indicate that autoloader strategy stub
     * has been registred properly.
     *
     * @return boolean
     */
    private function registrationIsCorrect()
    {
        $lastRegisteredAutoloader = array_pop(spl_autoload_functions());
        $autoloaderClass = $this->getMockedClass($lastRegisteredAutoloader[0]);
        $autoloaderMethod = $lastRegisteredAutoloader[1];

        $classIsCorrect = ($autoloaderClass === 'AutoloadingStrategyInterface');
        $methodIsCorrect = ($autoloaderMethod === 'loadClass');
        $registrationIsCorrect = $classIsCorrect && $methodIsCorrect;

        return $registrationIsCorrect;
    }

    /**
     * Extract mocked class name
     * from the mock object.
     *
     * @param mixed $mock
     * @return string
     */
    protected function getMockedClass($mock)
    {
        $mockClass = get_class($mock);
        $mockedClass = (substr(substr($mockClass, 5), 0, -9));

        return $mockedClass;
    }

    /**
     * Test Exorg\Autoloader\Autoloader class exists.
     */
    public function testConstructorReturnsProperInstance()
    {
        $autoloader = new Autoloader();

        $this->assertInstanceOf('Exorg\Autoloader\Autoloader', $autoloader);
    }

    /**
     * Test setAutoloadingStrategy method
     * doesn't accept argument of class
     * that does not implement Exorg\Autoloader\IntrfaceAutoloadingStrategy iterface.
     */
    public function testSetAutoloadingStrategyDoesNotAcceptsArgument()
    {
        $this->expectException('TypeError');

        $this->autoloader->setAutoloadingStrategy(new \stdClass());
    }

    /**
     * Test setAutoloadingStrategy method
     * receives argument of Exorg\Autoloader\IntrfaceAutoloadingStrategy iterface.
     */
    public function testSetAutoloadingStrategyReceivesCorrectArgument()
    {
        $autoloadingStrategyMock = $this->getMockBuilder('Exorg\Autoloader\AutoloadingStrategyInterface')
            ->getMock();

        $this->autoloader->setAutoloadingStrategy($autoloadingStrategyMock);
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
}
