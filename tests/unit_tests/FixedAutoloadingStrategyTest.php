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
 * Fixed autoloading strategy test.
 * PHPUnit test class for FixedAutoloadingStrategy class.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 *
 * @runTestsInSeparateProcesses
 */
class FixedAutoloadingStrategyTest extends AbstractAutoloadingStrategyTestCase
{
    private const FIXED_AUTOLOADING_STRATEGY_FULLY_QUALIFIED_CLASS_NAME = 'ExOrg\Autoloader\FixedAutoloadingStrategy';

    /**
     * Test ExOrg\Autoloader\FixedAutoloadingStrategy class exists.
     */
    public function testConstructorReturnsProperInstance()
    {
        $strategy = new FixedAutoloadingStrategy();

        $this->assertInstanceOf(self::FIXED_AUTOLOADING_STRATEGY_FULLY_QUALIFIED_CLASS_NAME, $strategy);
    }

    /**
     * Test FixedAutoloadingStrategy class implements AutoloadingStrategyInterface.
     */
    public function testImplementsAutoloadingStrategyInterace()
    {
        $strategy = new FixedAutoloadingStrategy();

        $this->assertImplements($strategy, self::AUTOLOADING_STRATEGY_FULLY_QUALIFIED_INTERFACE_NAME);
    }

    /**
     * Test for nonexistent path.
     */
    public function testForNonexistentPath()
    {
        $path = $this->getFullFixturePath('/nonexistent/Dummy/ComponentExistent.php');

        $this->strategy->registerClassPath('Dummy\ComponentExistent', $path);

        $this->assertClassDoesNotExist('\Dummy\ComponentExistent');
    }

    /**
     * Test for empty path.
     */
    public function testForEmptyPath()
    {
        $path = $this->getFullFixturePath('/empty/Dummy/ComponentExistent.php');

        $this->strategy->registerClassPath('Dummy\ComponentExistent', $path);

        $this->assertClassDoesNotExist('\Dummy\ComponentExistent');
    }

    /**
     * Test for nonexistent class file.
     */
    public function testForNonexistentClassFile()
    {
        $path = $this->getFullFixturePath('/src/Dummy/ComponentNonexistent.php');

        $this->strategy->registerClassPath('Dummy\ComponentNonexistent', $path);

        $this->assertClassDoesNotExist('\Dummy\ComponentNonexistent');
    }

    /**
     * Test for not registered class.
     */
    public function testForUnregisteredClass()
    {
        $this->assertClassDoesNotExist('\Dummy\ComponentExistent');
    }

    /**
     * Test for nonexistent namespace.
     */
    public function testForNonexistentNamespace()
    {
        $path = $this->getFullFixturePath('/src/Dummy/ComponentExistent.php');

        $this->strategy->registerClassPath('Nonexistent\ComponentExistent', $path);

        $this->assertClassDoesNotExist('\Dummy\ComponentExistent');
    }

    /**
     * Test for existent class file.
     */
    public function testForExistentClassFile()
    {
        $path = $this->getFullFixturePath('/src/Dummy/ComponentExistent.php');

        $this->strategy->registerClassPath('Dummy\ComponentExistent', $path);

        $this->assertClassIsInstantiable('\Dummy\ComponentExistent');
    }

    /**
     * Test for namespace with backslash on the beginning.
     */
    public function testForNamespaceWithBackslashOnBeginning()
    {
        $path = $this->getFullFixturePath('/src/Dummy/ComponentExistent.php');

        $this->strategy->registerClassPath('\Dummy\ComponentExistent', $path);

        $this->assertClassDoesNotExist('\Dummy\ComponentExistent');
    }

    /**
     * Test for namespace with backslash on the end.
     */
    public function testForNamespaceWithBackslashOnEnd()
    {
        $path = $this->getFullFixturePath('/src/Dummy/ComponentExistent.php');

        $this->strategy->registerClassPath('Dummy\ComponentExistent\\', $path);

        $this->assertClassDoesNotExist('\Dummy\ComponentExistent');
    }

    /**
     * Test for namespace with backslash on the beginning and the end.
     */
    public function testForNamespaceWithBackslashOnBeginningAndEnd()
    {
        $path = $this->getFullFixturePath('/src/Dummy/ComponentExistent.php');

        $this->strategy->registerClassPath('\Dummy\ComponentExistent\\', $path);

        $this->assertClassDoesNotExist('\Dummy\ComponentExistent');
    }

    /**
     * Test for fully qualified class name without backslash on the beginning.
     */
    public function testForFQClassNameWithoutBackslashOnBeginning()
    {
        $path = $this->getFullFixturePath('/src/Dummy/ComponentExistent.php');

        $this->strategy->registerClassPath('Dummy\ComponentExistent', $path);

        $this->assertClassIsInstantiable('Dummy\ComponentExistent');
    }

    /**
     * Test for the class specification with one level of nesting
     * without namespace.
     */
    public function testFor1nClassWithoutNamespace()
    {
        $path = $this->getFullFixturePath('/src/Dummy/ComponentNotNestedWithoutNS.php');

        $this->strategy->registerClassPath('ComponentNotNestedWithoutNS', $path);

        $this->assertClassIsInstantiable('\ComponentNotNestedWithoutNS');
    }

    /**
     * Test for the class specification with one level of nesting
     * with namespace.
     */
    public function testFor1nClassWithNamespace()
    {
        $path = $this->getFullFixturePath('/src/Dummy/ComponentNotNestedWithNS.php');

        $this->strategy->registerClassPath('Dummy\ComponentNotNestedWithNS', $path);

        $this->assertClassIsInstantiable('\Dummy\ComponentNotNestedWithNS');
    }

    /**
     * Test for the class specification with two levels of nesting
     * without namespace.
     */
    public function testFor2nClassWithoutNamespace()
    {
        $path = $this->getFullFixturePath('/src/Dummy/Core/ComponentNestedWithoutNS.php');

        $this->strategy->registerClassPath('ComponentNestedWithoutNS', $path);

        $this->assertClassIsInstantiable('\ComponentNestedWithoutNS');
    }

        /**
     * Test for the class specification with two levels of nesting
     * with namespace.
     */
    public function testFor2nClassWithNamespace()
    {
        $path = $this->getFullFixturePath('/src/Dummy/Core/ComponentNestedWithNS.php');

        $this->strategy->registerClassPath('Dummy\Core\ComponentNestedWithNS', $path);

        $this->assertClassIsInstantiable('\Dummy\Core\ComponentNestedWithNS');
    }

    /**
     * Test for the class with underscored namespace name.
     */
    public function testForClassWithUnderscoredNamespaceName()
    {
        $path = $this->getFullFixturePath('/src/Dummy/Underscored_Section/AdditionalComponent.php');

        $this->strategy->registerClassPath('Dummy\Underscored_Section\AdditionalComponent', $path);

        $this->assertClassIsInstantiable('\Dummy\Underscored_Section\AdditionalComponent');
    }

    /**
     * Test for the class with underscored name.
     */
    public function testForClassWithUnderscoredName()
    {
        $path = $this->getFullFixturePath('/src/Dummy/Core/Underscored_Component.php');

        $this->strategy->registerClassPath('Dummy\Core\Underscored_Component', $path);

        $this->assertClassIsInstantiable('\Dummy\Core\Underscored_Component');
    }

    /**
     * Test for many class pathes registered.
     */
    public function testManyClasses()
    {
        $path1 = $this->getFullFixturePath('/src/Dummy/ComponentExistent.php');
        $path2 = $this->getFullFixturePath('/src/Dummy/ComponentNotNestedWithoutNS.php');
        $path3 = $this->getFullFixturePath('/src/Dummy/ComponentNotNestedWithNS.php');
        $path4 = $this->getFullFixturePath('/src/Dummy/Core/ComponentNestedWithoutNS.php');
        $path5 = $this->getFullFixturePath('/src/Dummy/Core/ComponentNestedWithNS.php');
        $path6 = $this->getFullFixturePath('/src/Dummy/Underscored_Section/AdditionalComponent.php');
        $path7 = $this->getFullFixturePath('/src/Dummy/Core/Underscored_Component.php');

        $this->strategy->registerClassPath('Dummy\ComponentExistent', $path1);
        $this->strategy->registerClassPath('ComponentNotNestedWithoutNS', $path2);
        $this->strategy->registerClassPath('Dummy\ComponentNotNestedWithNS', $path3);
        $this->strategy->registerClassPath('ComponentNestedWithoutNS', $path4);
        $this->strategy->registerClassPath('Dummy\Core\ComponentNestedWithNS', $path5);
        $this->strategy->registerClassPath('Dummy\Underscored_Section\AdditionalComponent', $path6);
        $this->strategy->registerClassPath('Dummy\Core\Underscored_Component', $path7);

        $this->assertClassDoesNotExist('\Dummy\ComponentNonexistent');
        $this->assertClassIsInstantiable('\Dummy\ComponentExistent');
        $this->assertClassIsInstantiable('\ComponentNotNestedWithoutNS');
        $this->assertClassIsInstantiable('\Dummy\ComponentNotNestedWithNS');
        $this->assertClassIsInstantiable('\ComponentNestedWithoutNS');
        $this->assertClassIsInstantiable('\Dummy\Core\ComponentNestedWithNS');
        $this->assertClassIsInstantiable('\Dummy\Underscored_Section\AdditionalComponent');
        $this->assertClassIsInstantiable('\Dummy\Core\Underscored_Component');
    }

    /**
     * Provide autoloading strategy instance
     * against which the tests will be running.
     *
     * @return AutoloadingStrategyInterface
     */
    protected function provideStrategyIstance(): AutoloadingStrategyInterface
    {
        return new FixedAutoloadingStrategy();
    }
}
