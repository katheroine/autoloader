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
 * PEAR autoloading strategy test.
 * PHPUnit test class for PearAutoloadingStrategy class.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class PearAutoloadingStrategyTest extends AbstractAutoloadingStrategyTestCase
{
    private const PEAR_AUTOLOADING_STRATEGY_FULLY_QUALIFIED_CLASS_NAME = 'ExOrg\Autoloader\PearAutoloadingStrategy';

    /**
     * Test ExOrg\Autoloader\PearAutoloadingStrategy class exists.
     */
    public function testConstructorReturnsProperInstance()
    {
        $strategy = new PearAutoloadingStrategy();

        $this->assertInstanceOf(self::PEAR_AUTOLOADING_STRATEGY_FULLY_QUALIFIED_CLASS_NAME, $strategy);
    }

    /**
     * Test FixedAutoloadingStrategy class implements AutoloadingStrategyInterface.
     */
    public function testImplementsAutoloadingStrategyInterace()
    {
        $strategy = new PearAutoloadingStrategy();

        $this->assertImplements($strategy, self::AUTOLOADING_STRATEGY_FULLY_QUALIFIED_INTERFACE_NAME);
    }

    /**
     * Test for nonexistent registered path.
     */
    public function testForNonexistentPath()
    {
        $path = $this->getFullFixturePath('/nonexistent');

        $this->strategy->registerPrefixPath('Dummy', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentNotRegistered');
    }

    /**
     * Test for empty registered path.
     */
    public function testForEmptyPath()
    {
        $path = $this->getFullFixturePath('/empty');

        $this->strategy->registerPrefixPath('Dummy', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentNotRegistered');
    }

    /**
     * Test for unregisterd path.
     */
    public function testForUnregisteredPath()
    {
        $path = $this->getFullFixturePath('/doc');

        $this->strategy->registerPrefixPath('Dummy_', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentNotRegistered');
    }

    /**
     * Test class with unregisterd prefix.
     */
    public function testForUnregisteredPrefix()
    {
        $path = $this->getFullFixturePath('/lib/classes');

        $this->strategy->registerPrefixPath('Nonexistent_', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentNotRegistered');
    }

    /**
     * Test for the class with one level of nesting.
     */
    public function testFor1nClass()
    {
        $path = $this->getFullFixturePath('/lib/classes');

        $this->strategy->registerPrefixPath('Dummy_', $path);

        $this->assertClassIsInstantiable('Dummy_ComponentNotNested');
    }

    /**
     * Test for the class with two levels of nesting.
     */
    public function testFor2nClass()
    {
        $path = $this->getFullFixturePath('/lib/classes');

        $this->strategy->registerPrefixPath('Dummy_', $path);

        $this->assertClassIsInstantiable('Dummy_Core_ComponentNested');
    }

    /**
     * Provide autoloading strategy instance
     * against which the tests will be running.
     *
     * @return AutoloadingStrategyInterface
     */
    protected function provideStrategyIstance(): AutoloadingStrategyInterface
    {
        return new PearAutoloadingStrategy();
    }
}
