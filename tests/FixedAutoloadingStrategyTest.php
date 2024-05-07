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
 */
class FixedAutoloadingStrategyTest extends AbstractAutoloadingStrategyTestCase
{
    /**
     * Test ExOrg\Autoloader\FixedAutoloadingStrategy class exists.
     */
    public function testConstructorReturnsProperInstance()
    {
        $strategy = new FixedAutoloadingStrategy();

        $this->assertInstanceOf('ExOrg\Autoloader\FixedAutoloadingStrategy', $strategy);
    }

    /**
     * Test FixedAutoloadingStrategy class implements AutoloadingStrategyInterface.
     */
    public function testImplementsAutoloadingStrategyInterace()
    {
        $strategy = new FixedAutoloadingStrategy();

        $this->assertImplements($strategy, 'ExOrg\Autoloader\AutoloadingStrategyInterface');
    }

    /**
     * Test for nonexistent registered path.
     */
    public function testForNonexistentPath()
    {
        $path = $this->getFullFixturePath('/nonexistent');

        $this->strategy->registerClassPath('NotCalledClass', $path);

        $this->assertClassDoesNotExist('NonexistentClass');
    }

    /**
     * Test for empty registered path.
     */
    public function testForEmptyPath()
    {
        $path = $this->getFullFixturePath('/empty');

        $this->strategy->registerClassPath('NotCalledClass', $path);

        $this->assertClassDoesNotExist('NonexistentClass');
    }

    /**
     * Test class not existent in registered path is not found.
     */
    public function testForNonexistentClass()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerClassPath('NonexistentClass', $path);

        $this->assertClassDoesNotExist('NonexistentClass');
    }

    /**
     * Test class not registered with registered path is not found.
     */
    public function testForUnregisteredClass()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerClassPath('RegisteredClass', $path);

        $this->assertClassDoesNotExist('NotRegisteredClass');
    }

    /**
     * Test for the class specification with one level of nesting
     * with no namespace.
     */
    public function testFor1nClassAndNoNamespace()
    {
        $path = $this->getFullFixturePath('/src/ComponentNotNestedNoNS.php');

        $this->strategy->registerClassPath('ComponentNotNestedNoNS', $path);

        $this->assertClassIsInstantiable('ComponentNotNestedNoNS');
    }

    /**
     * Test for the class specification with one level of nesting
     * and namespace with one level of nesting.
     */
    public function testFor1nClassAnd1nNamespace()
    {
        $path = $this->getFullFixturePath('/src/ComponentNotNestedWithNS.php');

        $this->strategy->registerClassPath('Dummy\ComponentNotNestedWithNS', $path);

        $this->assertClassIsInstantiable('Dummy\ComponentNotNestedWithNS');
    }

    /**
     * Test for the class specification with two levels of nesting
     * with no namespace.
     */
    public function testFor2nClassAndNoNamespace()
    {
        $path = $this->getFullFixturePath('/src/Core/ComponentNestedNoNS.php');

        $this->strategy->registerClassPath('ComponentNestedNoNS', $path);

        $this->assertClassIsInstantiable('ComponentNestedNoNS');
    }

    /**
     * Test for the class specification with two levels of nesting
     * with namespace with one level of nesting.
     */
    public function testFor2nClassAnd1nNamespace()
    {
        $path = $this->getFullFixturePath('/src/Core/ComponentNestedWithNS.php');

        $this->strategy->registerClassPath('Dummy\ComponentNestedWithNS', $path);

        $this->assertClassIsInstantiable('Dummy\ComponentNestedWithNS');
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
