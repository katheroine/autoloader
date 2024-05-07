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
 * PSR-4 autoloading strategy test.
 * PHPUnit test class for Psr4AutoloadingStrategy class.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class Psr4AutoloadingStrategyTest extends AbstractAutoloadingStrategyTestCase
{
    private const PSR4_AUTOLOADING_STRATEGY_FULLY_QUALIFIED_CLASS_NAME = 'ExOrg\Autoloader\Psr4AutoloadingStrategy';

    /**
     * Test ExOrg\Autoloader\Psr4AutoloadingStrategy class exists.
     */
    public function testConstructorReturnsProperInstance()
    {
        $strategy = new Psr4AutoloadingStrategy();

        $this->assertInstanceOf(self::PSR4_AUTOLOADING_STRATEGY_FULLY_QUALIFIED_CLASS_NAME, $strategy);
    }

    /**
     * Test Psr0AutoloadingStrategy class implements AutoloadingStrategyInterface.
     */
    public function testImplementsAutoloadingStrategyInterace()
    {
        $strategy = new Psr4AutoloadingStrategy();

        $this->assertImplements($strategy, self::AUTOLOADING_STRATEGY_FULLY_QUALIFIED_INTERFACE_NAME);
    }

    /**
     * Test for nonexisting path.
     */
    public function testForNonexistentPath()
    {
        $path = $this->getFullFixturePath('/nonexistent');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassDoesNotExist('Dummy\ComponentExistent');
    }

    /**
     * Test for empty path.
     */
    public function testForEmptyPath()
    {
        $path = $this->getFullFixturePath('/empty');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassDoesNotExist('Dummy\ComponentExistent');
    }

    /**
     * Test for nonexistent class file.
     */
    public function testForNonexistentClassFile()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassDoesNotExist('Dummy\ComponentNonexistent');
    }

    /**
     * Test for not registered namespace.
     */
    public function testForUnregisteredNamespace()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Unregistered', $path);

        $this->assertClassDoesNotExist('Dummy\Component');
    }

    /**
     * Test for namespace registration with one level of nesting
     * and class specification with one level of nesting.
     */
    public function testFor1nNamespaceAnd1nClass()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassIsInstantiable('Vendor\Package\Dummy\ComponentNotNested');
    }

    /**
     * Test for namespace registration with one level of nesting
     * and class specification with two levels of nesting.
     */
    public function testFor1nNamespaceAnd2nClass()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassIsInstantiable('Vendor\Package\Dummy\Core\ComponentNested1');
    }

    /**
     * Test for namespace registration with two levels of nesting
     * and class specification with two levels of nesting.
     */
    public function testFor2nNamespaceAnd2nClass()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package\Dummy', $path);

        $this->assertClassDoesNotExist('Vendor\Package\Dummy\Core\ComponentNested2');
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
     * Provide autoloading strategy instance
     * against which the tests will be running.
     *
     * @return AutoloadingStrategyInterface
     */
    protected function provideStrategyIstance(): AutoloadingStrategyInterface
    {
        return new Psr4AutoloadingStrategy();
    }
}
