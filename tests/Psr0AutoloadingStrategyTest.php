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
 * Psr0AutoloadingStrategyTest.
 * PHPUnit test class for Psr0AutoloadingStrategy class.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class Psr0AutoloadingStrategyTest extends AbstractAutoloadingStrategyTestCase
{
    /**
     * Initialise strategy fixture.
     */
    protected function initialiseStrategy()
    {
        $this->strategy = new Psr0AutoloadingStrategy();
    }

    /**
     * Test Exorg\Autoloader\Psr0AutoloadingStrategy class exists.
     */
    public function testConstructorReturnsProperInstance()
    {
        $strategy = new Psr0AutoloadingStrategy();

        $this->assertInstanceOf('Exorg\Autoloader\Psr0AutoloadingStrategy', $strategy);
    }

    /**
     * Test Psr0AutoloadingStrategy class implements AutoloadingStrategyInterface.
     */
    public function testImplementsAutoloadingStrategyInterace()
    {
        $strategy = new Psr0AutoloadingStrategy();

        $this->assertImplements($strategy, 'Exorg\Autoloader\AutoloadingStrategyInterface');
    }

    /**
     * Test for nonexisting path
     * with namespace separator.
     */
    public function testForNonexistentPathWithNSSep()
    {
        $path = $this->getFullFixturePath('/nonexistent');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassDoesNotExist('Dummy\ComponentNonexistent');
    }

    /**
     * Test for nonexisting path
     * with underscore separator.
     */
    public function testForNonexistentPathWithUSSep()
    {
        $path = $this->getFullFixturePath('/nonexistent');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentNonexistent');
    }

    /**
     * Test for empty path
     * with namespace separator.
     */
    public function testForEmptyPathWithNSSep()
    {
        $path = $this->getFullFixturePath('/empty');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassDoesNotExist('Dummy\ComponentNonexistent');
    }

    /**
     * Test for empty path
     * with underscore separator.
     */
    public function testForEmptyPathWithUSSep()
    {
        $path = $this->getFullFixturePath('/empty');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentNonexistent');
    }

    /**
     * Test for nonexistent class file
     * with namespace separator.
     */
    public function testForNonexistentClassFileWithNSSep()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassDoesNotExist('Dummy\ComponentNonexistent');
    }

    /**
     * Test for nonexistent class file
     * with underscore separator.
     */
    public function testForNonexistentClassFileWithUSSep()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentNonexistent');
    }

    /**
     * Test for not registered namespace
     * with namespace separator.
     */
    public function testForUnregisteredNamespaceWithNSSep()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Unregistered', $path);

        $this->assertClassDoesNotExist('Dummy\Component');
    }

    /**
     * Test for not registered namespace
     * with underscore separator.
     */
    public function testForUnregisteredNamespaceWithUSSep()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Unregistered', $path);

        $this->assertClassDoesNotExist('Dummy_Component');
    }

    /**
     * Test for registered namespace but unused in class specification.
     */
    public function testForUnusedNamespace()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassDoesNotExist('Component');
    }

    /**
     * Test for namespace registration with one level of nesting
     * and class specification with one level of nesting
     * with namespace separator.
     */
    public function testFor1nNamespaceAnd1nClassWithNSSep()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassIsInstantiable('Dummy\ComponentNotNested');
    }

    /**
     * Test for namespace registration with one level of nesting
     * and class specification with one level of nesting
     * with underscore separator.
     */
    public function testFor1nNamespaceAnd1nClassWithUSSep()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassIsInstantiable('Dummy_ComponentNotNested');
    }

    /**
     * Test for namespace registration with one level of nesting
     * and class specification with two levels of nesting
     * with namespace separator.
     */
    public function testFor1nNamespaceAnd2nClassWithNSSep()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassIsInstantiable('Dummy\Core\ComponentNested');
    }

    /**
     * Test for namespace registration with one level of nesting
     * and class specification with two levels of nesting
     * with underscore separator.
     */
    public function testFor1nNamespaceAnd2nClassWithUSSep()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassIsInstantiable('Dummy_Core_ComponentNested');
    }

    /**
     * Test for namespace registration with two levels of nesting
     * and class specification with two levels of nesting
     * with namespace separator.
     */
    public function testFor2nNamespaceAnd2nClassWithNSSep()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy\Core', $path);

        $this->assertClassIsInstantiable('Dummy\Core\ComponentNested');
    }

    /**
     * Test for namespace registration with two levels of nesting
     * and class specification with two levels of nesting
     * with underscore separator.
     */
    public function testFor2nNamespaceAnd2nClassWithUSSep()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy\Core', $path);

        $this->assertClassIsInstantiable('Dummy_Core_ComponentNested');
    }

    /**
     * Test for the class with one level of nesting
     * and class specification with two levels of nesting
     * with mixed separators.
     */
    public function testFor1nNamespaceAnd3nClassWithMXSep()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassIsInstantiable('Dummy\Core\Sub_ComponentNestedSub');
    }

    /**
     * Test for the class with one level of nesting
     * and class specification with two levels of nesting
     * with underscored package name.
     */
    public function testFor1nNamespaceAnd2nClassWithUSPackage()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassIsInstantiable('Dummy\Additional_Package\AdditionalComponentNested');
    }

    /**
     * Test for the class with one level of nesting
     * and class specification with two levels of nesting
     * with mixed separators
     * and underscored package name.
     */
    public function testFor1nNamespaceAnd2nClassWithUSSepAndUSPackage()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassIsInstantiable('Dummy\Additional_Package\Sub_AdditionalComponentNestedSub');
    }
}
