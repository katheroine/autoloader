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
 * Recursive autoloading strategy test.
 * PHPUnit test class for RecursiveAutoloadingStrategy class.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 *
 * @runTestsInSeparateProcesses
 */
class RecursiveAutoloadingStrategyTest extends AbstractAutoloadingStrategyTestCase
{
    private const RECURSIVE_AUTOLOADING_STRATEGY_FULLY_QUALIFIED_CLASS_NAME = 'ExOrg\Autoloader\RecursiveAutoloadingStrategy';

    /**
     * Test ExOrg\Autoloader\RecursiveAutoloadingStrategy class exists.
     */
    public function testConstructorReturnsProperInstance()
    {
        $strategy = new RecursiveAutoloadingStrategy();

        $this->assertInstanceOf(self::RECURSIVE_AUTOLOADING_STRATEGY_FULLY_QUALIFIED_CLASS_NAME, $strategy);
    }

    /**
     * Test FixedAutoloadingStrategy class implements AutoloadingStrategyInterface.
     */
    public function testImplementsAutoloadingStrategyInterace()
    {
        $strategy = new RecursiveAutoloadingStrategy();

        $this->assertImplements($strategy, self::AUTOLOADING_STRATEGY_FULLY_QUALIFIED_INTERFACE_NAME);
    }

    /**
     * Test for nonexistent registered path.
     */
    public function testForNonexistentPath()
    {
        $path = $this->getFullFixturePath('/nonexistent');

        $this->strategy->registerPath($path);

        $this->assertClassDoesNotExist('\Dummy\ComponentExistent');
    }

    /**
     * Test for empty path.
     */
    public function testForEmptyPath()
    {
        $path = $this->getFullFixturePath('/empty');

        $this->strategy->registerPath($path);

        $this->assertClassDoesNotExist('\Dummy\ComponentExistent');
    }

    /**
     * Test for nonexistent class file.
     */
    public function testForNonexistentClass()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassDoesNotExist('\Dummy\ComponentNonexistent');
    }

    /**
     * Test for existent class file.
     */
    public function testForExistentClass()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('\Dummy\ComponentExistent');
    }

    /**
     * Test for fully qualified class name without backslash on the beginning.
     */
    public function testForFQClassNameWithoutBackslashOnBeginning()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('Dummy\ComponentExistent');
    }

    /**
     * Test for the class specification without nesting
     * without namespace.
     */
    public function testFor0nClassWithoutNamespace()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('\ComponentNotNestedWithoutNS');
    }

    /**
     * Test for the class specification without nesting
     * with plain namespace.
     */
    public function testFor0nClassWithPlainNamespace()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('\Dummy\ComponentNotNestedWithPlainNS');
    }

    /**
     * Test for the class specification without nesting
     * with complex namespace.
     */
    public function testFor0nClassWithComplexNamespace()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('\Dummy\Core\ComponentNotNestedWithComplexNS');
    }

    /**
     * Test for the class specification with one level of nesting
     * without namespace.
     */
    public function testFor1nClassWithoutNamespace()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('\ComponentNestedWithoutNS');
    }

    /**
     * Test for the class specification with one level of nesting
     * with plain namespace.
     */
    public function testFor1nClassWithPlainNamespace()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('\Dummy\ComponentNestedWithPlainNS');
    }

    /**
     * Test for the class specification with one level of nesting
     * with complex namespace.
     */
    public function testFor1nClassWithComplexNamespace()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('\Dummy\Core\ComponentNestedWithComplexNS');
    }

    /**
     * Test for the class specification with two levels of nesting
     * without namespace.
     */
    public function testFor2nClassWithoutNamespace()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('\ComponentDoubleNestedWithoutNS');
    }

    /**
     * Test for the class specification with two levels of nesting
     * with plain namespace.
     */
    public function testFor2nClassWithPlainNamespace()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('\Dummy\ComponentDoubleNestedWithPlainNS');
    }

    /**
     * Test for the class specification with two levels of nesting
     * with complex namespace.
     */
    public function testFor2nClassWithComplexNamespace()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('\Dummy\Core\ComponentDoubleNestedWithComplexNS');
    }

    /**
     * Test for the class existent on two higher levels directory
     * than registered path.
     */
    public function testForClassExistentOn2HigherLevelsThanRegisteredPath()
    {
        $path = $this->getFullFixturePath('/directory-1/subdirectory-1-1');

        $this->strategy->registerPath($path);

        $this->assertClassDoesNotExist('\Dummy\ComponentExistent');
    }

    /**
     * Test for the class existent on one higher level directory
     * than registered path.
     */
    public function testForClassExistentOn1HigherLevelThanRegisteredPath()
    {
        $path = $this->getFullFixturePath('/directory-1');

        $this->strategy->registerPath($path);

        $this->assertClassDoesNotExist('\Dummy\ComponentExistent');
    }

    /**
     * Test for the class with underscored namespace name.
     */
    public function testForClassWithUnderscoredNamespaceName()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('\Dummy\Underscored_Section\AdditionalComponent');
    }

    /**
     * Test for the class with underscored name.
     */
    public function testForClassWithUnderscoredName()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('\Dummy\Underscored_Component');
    }

    /**
     * Test for many classes from the same directory path registered.
     */
    public function testManyClasses()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassDoesNotExist('\Dummy\ComponentNonexistent');
        $this->assertClassIsInstantiable('\Dummy\ComponentExistent');
        $this->assertClassIsInstantiable('\ComponentNotNestedWithoutNS');
        $this->assertClassIsInstantiable('\Dummy\ComponentNotNestedWithPlainNS');
        $this->assertClassIsInstantiable('\Dummy\Core\ComponentNotNestedWithComplexNS');
        $this->assertClassIsInstantiable('\ComponentNestedWithoutNS');
        $this->assertClassIsInstantiable('\Dummy\ComponentNestedWithPlainNS');
        $this->assertClassIsInstantiable('\Dummy\Core\ComponentNestedWithComplexNS');
        $this->assertClassIsInstantiable('\ComponentDoubleNestedWithoutNS');
        $this->assertClassIsInstantiable('\Dummy\ComponentDoubleNestedWithPlainNS');
        $this->assertClassIsInstantiable('\Dummy\Core\ComponentDoubleNestedWithComplexNS');
    }

    /**
     * Test for namespace registration with two directory paths registered.
     */
    public function testForTwoPathsAndOneNamespace()
    {
        $path1 = $this->getFullFixturePath('/directory-1');
        $path2 = $this->getFullFixturePath('/directory-2');

        $this->strategy->registerPath($path1);
        $this->strategy->registerPath($path2);

        $this->assertClassIsInstantiable('\Dummy\ComponentA');
        $this->assertClassIsInstantiable('\Dummy\ComponentB');
        $this->assertClassIsInstantiable('\Dummy\Core\ComponentC');
        $this->assertClassIsInstantiable('\Dummy\Core\ComponentD');
    }

    /**
     * Provide autoloading strategy instance
     * against which the tests will be running.
     *
     * @return AutoloadingStrategyInterface
     */
    protected function provideStrategyIstance(): AutoloadingStrategyInterface
    {
        return new RecursiveAutoloadingStrategy();
    }
}
