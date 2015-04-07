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
 * AutoloaderWithPsr0AutoloadingStrategyTest.
 * PHPUnit test class for Autloader with Psr0AutoloadingStrategy class.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class AutoloaderWithPsr0AutoloadingStrategyTest extends AbstractAutoloaderWithAutoloadingStrategyTest
{
    /**
     * Initialise strategy fixture.
     */
    protected function initialiseStrategy()
    {
        $this->strategy = new Psr0AutoloadingStrategy();
    }

    /**
     * Test registerNamespacePath method
     * for nonexistent class file
     * with namespace separator.
     */
    public function testForNonexistentClassFileWithNSSep()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassDoesNotExist('Dummy\ComponentNonexistent');
    }

    /**
     * Test registerNamespacePath method
     * for nonexistent class file
     * with underscore separator.
     */
    public function testForNonexistentClassFileWithUSSep()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentNonexistent');
    }

    /**
     * Test registerNamespacePath method
     * for nonexisting path
     * with namespace separator.
     */
    public function testForNonexistentPathWithNSSep()
    {
        $path = $this->getFullFixturePath('/nonexistent');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassDoesNotExist('Dummy\ComponentExistent');
    }

    /**
     * Test registerNamespacePath method
     * for nonexisting path
     * with underscore separator.
     */
    public function testForNonexistentPathWithUSSep()
    {
        $path = $this->getFullFixturePath('/nonexistent');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentExistent');
    }

    /**
     * Test registerNamespacePath method
     * for not registered namespace
     * with namespace separator.
     */
    public function testForUnregisteredNamespaceWithNSSep()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Unregistered', $path);

        $this->assertClassDoesNotExist('Dummy\Component');
    }

    /**
     * Test registerNamespacePath method
     * for not registered namespace
     * with underscore separator.
     */
    public function testForUnregisteredNamespaceWithUSSep()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Unregistered', $path);

        $this->assertClassDoesNotExist('Dummy_Component');
    }

    /**
     * Test registerNamespacePath method
     * for registered namespace but unused in class specification.
     */
    public function testForUnusedNamespace()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassDoesNotExist('Component');
    }

    /**
     * Test registerNamespacePath method
     * for namespace registration with one level of nesting
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
     * Test registerNamespacePath method
     * for namespace registration with one level of nesting
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
     * Test registerNamespacePath method
     * for namespace registration with one level of nesting
     * and class specification with two levels of nesting
     * with namespace separator.
     */
    public function testFor1NestedNamespaceAnd2NestedClassWithNSSep()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassIsInstantiable('Dummy\Core\ComponentNested');
    }

    /**
     * Test registerNamespacePath method
     * for namespace registration with one level of nesting
     * and class specification with two levels of nesting
     * with underscore separator.
     */
    public function testFor1NestedNamespaceAnd2NestedClassWithUSSep()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassIsInstantiable('Dummy_Core_ComponentNested');
    }

    /**
     * Test registerNamespacePath method
     * for namespace registration with two levels of nesting
     * and class specification with two levels of nesting
     * with namespace separator.
     */
    public function testFor2NestedNamespaceAnd2NestedClassWithNSSep()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy\Core', $path);

        $this->assertClassIsInstantiable('Dummy\Core\ComponentNested');
    }

    /**
     * Test registerNamespacePath method
     * for namespace registration with two levels of nesting
     * and class specification with two levels of nesting
     * with underscore separator.
     */
    public function testFor2NestedNamespaceAnd2NestedClassWithUSSep()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy\Core', $path);

        $this->assertClassIsInstantiable('Dummy_Core_ComponentNested');
    }

    /**
     * Test registerNamespacePath method
     * for the class with one level of nesting
     * and class specification with two levels of nesting
     * with mixed separators.
     */
    public function testFor1NestedNamespaceAnd3NestedClassWithMXSep()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassIsInstantiable('Dummy\Core\Sub_ComponentNestedSub');
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

    /**
     * Test registerNamespacePath method
     * for the class with one level of nesting
     * and class specification with two levels of nesting
     * with mixed separators
     * and underscored package name.
     */
    public function testFor1NestedNamespaceAnd2NestedClassWithUSSepAndUSPackage()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassIsInstantiable('Dummy\Additional_Package\Sub_AdditionalComponentNestedSub');
    }
}
