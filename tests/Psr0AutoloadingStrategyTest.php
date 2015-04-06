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
 * @license http://http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class Psr0AutoloadingStrategyTest extends AbstractAutoloadingStrategyTest
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
     * Test registerNamespacePath method
     * for nonxisting class.
     */
    public function testForUnexistentClass()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassDoesNotExist('\Dummy\ComponentNonExistent');
    }

    /**
     * Test registerNamespacePath method
     * for nonexisting path.
     */
    public function testForUnexistentPath()
    {
        $path = $this->getFullFixturePath('/nonexistent');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassDoesNotExist('\Dummy\ComponentNonExistent');
    }

    /**
     * Test registerNamespacePath method
     * for not registered namespace.
     */
    public function testForNotRegisteredNamespace()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassDoesNotExist('\Nonregistered\Component');
    }

    /**
     * Test registerNamespacePath method
     * for the class with one level of nesting.
     */
    public function testForClassWithOneLevelOfNesting()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassIsInstantiable('\Dummy\ComponentNotNested');
    }

    /**
     * Test registerNamespacePath method
     * for the class with two levels of nesting.
     */
    public function testForClassWithTwoLevelsOfNesting()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassIsInstantiable('\Dummy\Core\ComponentNested');
    }

    /**
     * Test registerNamespacePath method
     * for the class with three levels of nesting
     * and underscored name of the class.
     */
    public function testForClassWithThreeLevelsOfNestingAndClassNameUnderscored()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassIsInstantiable('\Dummy\Core\Sub_ComponentNestedSub');
    }

    /**
     * Test registerNamespacePath method
     * for the class with three levels of nesting
     * and underscored name of the package.
     */
    public function testForClassWithTreeLevelsOfNestingAndPackageNameUnderscored()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassIsInstantiable('Dummy\Additional_Package\AdditionalComponentNested');
    }

    /**
     * Test registerNamespacePath method
     * for the class with three levels of nesting,
     * underscored name of the class
     * and underscored name of the package.
     */
    public function testForClassWithThreeLevelsOfNestingAndClassNameUnderscoredAndPackageNameUnderscored()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Dummy', $path);

        $this->assertClassIsInstantiable('Dummy\Additional_Package\Sub_AdditionalComponentNestedSub');
    }
}
