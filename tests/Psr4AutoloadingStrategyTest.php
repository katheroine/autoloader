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
 * Psr4AutoloadingStrategyTest.
 * PHPUnit test class for Psr4AutoloadingStrategy class.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class Psr4AutoloadingStrategyTest extends AbstractAutoloadingStrategyTest
{
    /**
     * Initialise strategy fixture.
     */
    protected function initialiseStrategy()
    {
        $this->strategy = new Psr4AutoloadingStrategy();
    }

    /**
     * Test Exorg\Autoloader\Psr4AutoloadingStrategy class exists.
     */
    public function testConstructorReturnsProperInstance()
    {
        $strategy = new Psr4AutoloadingStrategy();

        $this->assertInstanceOf('Exorg\Autoloader\Psr4AutoloadingStrategy', $strategy);
    }

    /**
     * Test Psr0AutoloadingStrategy class implements AutoloadingStrategyInterface.
     */
    public function testImplementsAutoloadingStrategyInterace()
    {
        $strategy = new Psr4AutoloadingStrategy();

        $this->assertImplements($strategy, 'Exorg\Autoloader\AutoloadingStrategyInterface');
    }

    /**
     * Test registerNamespacePath method
     * for nonexisting path.
     */
    public function testForNonexistentPath()
    {
        $path = $this->getFullFixturePath('/nonexistent');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassDoesNotExist('Dummy\ComponentExistent');
    }

    /**
     * Test registerNamespacePath method
     * for empty path.
     */
    public function testForEmptyPath()
    {
        $path = $this->getFullFixturePath('/empty');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassDoesNotExist('Dummy\ComponentExistent');
    }

    /**
     * Test registerNamespacePath method
     * for nonexistent class file.
     */
    public function testForNonexistentClassFile()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassDoesNotExist('Dummy\ComponentNonexistent');
    }

    /**
     * Test registerNamespacePath method
     * for not registered namespace.
     */
    public function testForUnregisteredNamespace()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Unregistered', $path);

        $this->assertClassDoesNotExist('Dummy\Component');
    }

    /**
     * Test registerNamespacePath method
     * for namespace registration with one level of nesting
     * and class specification with one level of nesting.
     */
    public function testFor1nNamespaceAnd1nClass()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassIsInstantiable('Vendor\Package\Dummy\ComponentNotNested');
    }

    /**
     * Test registerNamespacePath method
     * for namespace registration with one level of nesting
     * and class specification with two levels of nesting.
     */
    public function testFor1NestedNamespaceAnd2NestedClass()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassIsInstantiable('Vendor\Package\Dummy\Core\ComponentNested1');
    }

    /**
     * Test registerNamespacePath method
     * for namespace registration with two levels of nesting
     * and class specification with two levels of nesting.
     */
    public function testFor2NestedNamespaceAnd2NestedClass()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package\Dummy', $path);

        $this->assertClassDoesNotExist('Vendor\Package\Dummy\Core\ComponentNested2');
    }

    /**
     * Test registerNamespacePath method
     * for the class with one level of nesting
     * and class specification with two levels of nesting
     * with underscored package name.
     */
    public function testFor1NestedNamespaceAnd2NestedClassWithUSPackage()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassIsInstantiable('Dummy\Additional_Package\AdditionalComponentNested');
    }
}
