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
 * AutoloaderWithPearAutoloadingStrategyTest.
 * PHPUnit test class for Autloader with PearAutoloadingStrategy class.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class AutoloaderWithPearAutoloadingStrategyTest extends AbstractAutoloaderWithAutoloadingStrategyTest
{
    /**
     * Initialise strategy fixture.
     */
    protected function initialiseStrategy()
    {
        $this->strategy = new PearAutoloadingStrategy();
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
