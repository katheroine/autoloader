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
 * FixedAutoloadingStrategyTest.
 * PHPUnit test class for FixedAutoloadingStrategy class.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class FixedAutoloadingStrategyTest extends AbstractAutoloadingStrategyTest
{
    /**
     * Initialise strategy fixture.
     */
    protected function initialiseStrategy()
    {
        $this->strategy = new FixedAutoloadingStrategy();
    }

    /**
     * Test Exorg\Autoloader\FixedAutoloadingStrategy class exists.
     */
    public function testConstructorReturnsProperInstance()
    {
        $strategy = new FixedAutoloadingStrategy();

        $this->assertInstanceOf('Exorg\Autoloader\FixedAutoloadingStrategy', $strategy);
    }

    /**
     * Test FixedAutoloadingStrategy class implements AutoloadingStrategyInterface.
     */
    public function testImplementsAutoloadingStrategyInterace()
    {
        $strategy = new FixedAutoloadingStrategy();

        $this->assertImplements($strategy, 'Exorg\Autoloader\AutoloadingStrategyInterface');
    }

    /**
     * Test unregistered class is not found.
     */
    public function testForNotRegisteredClass()
    {
        $path = $this->getFullFixturePath('/src/NotCalledClass');

        $this->strategy->registerClassPath('NotCalledClass', $path);

        $this->assertClassExists('NotRegisteredClass');
    }

    /**
     * Test registerClassPath method
     * for the class with one level of nesting
     * with no namespace
     */
    public function testForClassWithOneLevelOfNestingWithNoNamespace()
    {
        $path = $this->getFullFixturePath('/src/ComponentNotNestedNoNS.php');

        $this->strategy->registerClassPath('ComponentNotNestedNoNS', $path);

        $this->assertClassIsInstantiable('\ComponentNotNestedNoNS');
    }

    /**
     * Test registerClassPath method
     * for the class with one level of nesting
     * with namespace
     */
    public function testForClassWithOneLevelOfNestingWithNamespace()
    {
        $path = $this->getFullFixturePath('/src/ComponentNotNestedWithNS.php');

        $this->strategy->registerClassPath('Dummy\ComponentNotNestedWithNS', $path);

        $this->assertClassIsInstantiable('\Dummy\ComponentNotNestedWithNS');
    }

    /**
     * Test registerClassPath method
     * for the class with two levels of nesting
     * with no namespace
     */
    public function testForClassWithTwoLevelsOfNestingWithNoNamespace()
    {
        $path = $this->getFullFixturePath('/src/Core/ComponentNestedNoNS.php');

        $this->strategy->registerClassPath('ComponentNestedNoNS', $path);

        $this->assertClassIsInstantiable('\ComponentNestedNoNS');
    }

    /**
     * Test registerClassPath method
     * for the class with two levels of nesting
     * with namespace
     */
    public function testForClassWithTwoLevelsOfNestingWithNamespace()
    {
        $path = $this->getFullFixturePath('/src/Core/ComponentNestedWithNS.php');

        $this->strategy->registerClassPath('Dummy\ComponentNestedWithNS', $path);

        $this->assertClassIsInstantiable('\Dummy\ComponentNestedWithNS');
    }
}
