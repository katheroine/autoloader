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
 * PSR-0 autoloading strategy test.
 * PHPUnit test class for Psr0AutoloadingStrategy class.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 *
 * @runTestsInSeparateProcesses
 */
class Psr0AutoloadingStrategyTest extends AbstractAutoloadingStrategyTestCase
{
    private const PSR0_AUTOLOADING_STRATEGY_FULLY_QUALIFIED_CLASS_NAME = 'ExOrg\Autoloader\Psr0AutoloadingStrategy';

    /**
     * Test ExOrg\Autoloader\Psr0AutoloadingStrategy class exists.
     */
    public function testConstructorReturnsProperInstance()
    {
        $strategy = new Psr0AutoloadingStrategy();

        $this->assertInstanceOf(self::PSR0_AUTOLOADING_STRATEGY_FULLY_QUALIFIED_CLASS_NAME, $strategy);
    }

    /**
     * Test Psr0AutoloadingStrategy class implements AutoloadingStrategyInterface.
     */
    public function testImplementsAutoloadingStrategyInterace()
    {
        $strategy = new Psr0AutoloadingStrategy();

        $this->assertImplements($strategy, self::AUTOLOADING_STRATEGY_FULLY_QUALIFIED_INTERFACE_NAME);
    }

    /**
     * Test for nonexistent path.
     */
    public function testForNonexistentPath()
    {
        $path = $this->getFullFixturePath('/nonexistent');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassDoesNotExist('\Vendor\Package\Dummy\ComponentExistent');
    }

    /**
     * Test for empty path.
     */
    public function testForEmptyPath()
    {
        $path = $this->getFullFixturePath('/empty');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassDoesNotExist('\Vendor\Package\Dummy\ComponentExistent');
    }

    /**
     * Test for nonexistent class file.
     */
    public function testForNonexistentClassFile()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassDoesNotExist('\Vendor\Package\Dummy\ComponentNonexistent');
    }

    /**
     * Test for not registered namespace.
     */
    public function testForUnregisteredNamespace()
    {
        $this->assertClassDoesNotExist('\Vendor\Package\Dummy\ComponentExistent');
    }

    /**
     * Test for unexistent namespace.
     */
    public function testForUnexistentNamespace()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Unexistent', $path);

        $this->assertClassDoesNotExist('\Vendor\Package\Dummy\ComponentExistent');
    }

    /**
     * Test for existent class file.
     */
    public function testForExistentClassFile()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\ComponentExistent');
    }

    /**
     * Test for namespace with backslash on the beginning.
     */
    public function testForNamespaceWithBackslashOnBeginning()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('\Vendor\Package', $path);

        $this->assertClassDoesNotExist('\Vendor\Package\Dummy\ComponentExistent');
    }

    /**
     * Test for namespace with backslash on the end.
     */
    public function testForNamespaceWithBackslashOnEnd()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package\\', $path);

        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\ComponentExistent');
    }

    /**
     * Test for namespace with backslash on the beginning and the end.
     */
    public function testForNamespaceWithBackslashOnBeginningAndEnd()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('\Vendor\Package\\', $path);

        $this->assertClassDoesNotExist('\Vendor\Package\Dummy\ComponentExistent');
    }

    /**
     * Test for fully qualified class name without backslash on the beginning.
     */
    public function testForFQClassNameWithoutBackslashOnBeginning()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassIsInstantiable('Vendor\Package\Dummy\ComponentExistent');
    }

    /**
     * Test for namespace registration with one level of nesting
     * and class specification with one level of nesting.
     */
    public function testFor1nNamespaceAnd1nClass()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\ComponentNotNested');
    }

    /**
     * Test for namespace registration with one level of nesting
     * and class specification with two levels of nesting.
     */
    public function testFor1nNamespaceAnd2nClass()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\Core\ComponentNested1');
    }

    /**
     * Test for namespace registration with two levels of nesting
     * and class specification with two levels of nesting.
     */
    public function testFor2nNamespaceAnd2nClass()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package\Dummy', $path);

        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\Core\ComponentNested2');
    }

    /**
     * Test for the class with underscored namespace name.
     */
    public function testForClassWithUnderscoredNamespaceName()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\Underscored_Section\AdditionalComponent');
    }

    /**
     * Test for the class with underscored name.
     */
    public function testForClassWithUnderscoredName()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\Core\Underscored_Component');
    }

    /**
     * Test for many classes from the same namespace and directory path registered.
     */
    public function testManyClassesFromTheSameNamespace()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassDoesNotExist('\Vendor\Package\Dummy\ComponentNonexistent');
        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\ComponentExistent');
        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\ComponentNotNested');
        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\Core\ComponentNested1');
        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\Core\ComponentNested2');
        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\Underscored_Section\AdditionalComponent');
        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\Core\Underscored_Component');
    }

    /**
     * Test for namespace registration with two directory paths registered
     * for the same namespace.
     */
    public function testForTwoPathsAndOneNamespace()
    {
        $path1 = $this->getFullFixturePath('/src');
        $path2 = $this->getFullFixturePath('/lib');

        $this->strategy->registerNamespacePath('Vendor\Package', $path1);
        $this->strategy->registerNamespacePath('Vendor\Package', $path2);

        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\ComponentA');
        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\ComponentB');
        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\Core\ComponentC');
        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\Core\ComponentD');
    }

    /**
     * Provide autoloading strategy instance
     * against which the tests will be running.
     *
     * @return AutoloadingStrategyInterface
     */
    protected function provideStrategyIstance(): AutoloadingStrategyInterface
    {
        return new Psr0AutoloadingStrategy();
    }
}
