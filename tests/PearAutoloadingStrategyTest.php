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
 * PearAutoloadingStrategyTest.
 * PHPUnit test class for PearAutoloadingStrategy class.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class PearAutoloadingStrategyTest extends AbstractAutoloadingStrategyTest
{
    /**
     * Initialise strategy fixture.
     */
    protected function initialiseStrategy()
    {
        $this->strategy = new PearAutoloadingStrategy();
    }

    /**
     * Test Exorg\Autoloader\PearAutoloadingStrategy class exists.
     */
    public function testConstructorReturnsProperInstance()
    {
        $strategy = new PearAutoloadingStrategy();

        $this->assertInstanceOf('Exorg\Autoloader\PearAutoloadingStrategy', $strategy);
    }

    /**
     * Test FixedAutoloadingStrategy class implements AutoloadingStrategyInterface.
     */
    public function testImplementsAutoloadingStrategyInterace()
    {
        $strategy = new PearAutoloadingStrategy();

        $this->assertImplements($strategy, 'Exorg\Autoloader\AutoloadingStrategyInterface');
    }

    /**
     * Test class is not found in nonexistent registerd path.
     */
    public function testForNonexistentRegisteredPath()
    {
        $path = $this->getFullFixturePath('/nonexistent');

        $this->strategy->registerPrefixPath('Dummy', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentNotRegistered');
    }

   /**
     * Test class is not found in empty registerd path.
     */
    public function testForEmptyRegisteredPath()
    {
        $path = $this->getFullFixturePath('/empty');

        $this->strategy->registerPrefixPath('Dummy', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentNotRegistered');
    }

    /**
     * Test class is not found in unregisterd path.
     */
    public function testForNotRegisteredPath()
    {
        $path = $this->getFullFixturePath('/doc');

        $this->strategy->registerPrefixPath('Dummy_', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentNotRegistered');
    }

    /**
     * Test class with unregisterd prefix.
     */
    public function testForNotRegisteredPrefix()
    {
        $path = $this->getFullFixturePath('/lib/classes');

        $this->strategy->registerPrefixPath('Nonexistent_', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentNotRegistered');
    }

    /**
     * Test registerPrefixPath method
     * for the class with one level of nesting.
     */
    public function testForClassWithOneLevelOfNesting()
    {
        $path = $this->getFullFixturePath('/lib/classes');

        $this->strategy->registerPrefixPath('Dummy_', $path);

        $this->assertClassIsInstantiable('Dummy_ComponentNotNested');
    }

    /**
     * Test registerPrefixPath method
     * for the class with two levels of nesting.
     */
    public function testForClassWithTwoLevelsOfNesting()
    {
        $path = $this->getFullFixturePath('/lib/classes');

        $this->strategy->registerPrefixPath('Dummy_', $path);

        $this->assertClassIsInstantiable('Dummy_Core_ComponentNested');
    }
}
