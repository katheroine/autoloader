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
 * AutoloaderWithFixedAutoloadingStrategyTest.
 * PHPUnit test class for Autloader with FixedAutoloadingStrategy class.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class AutoloaderWithFixedAutoloadingStrategyTest extends AbstractAutoloaderWithAutoloadingStrategyTest
{
    /**
     * Initialise strategy fixture.
     */
    protected function initialiseStrategy()
    {
        $this->strategy = new FixedAutoloadingStrategy();
    }

       /**
     * Test unregistered class is not found.
     */
    public function testForNotRegisteredClass()
    {
        $path = $this->getFullFixturePath('/src/NotCalledClass');

        $this->strategy->registerClassPath('NotCalledClass', $path);

        $this->assertClassDoesNotExist('NotRegisteredClass');
    }

    /**
     * Test registerClassPath method
     * for the class specification with one level of nesting
     * with no namespace
     */
    public function testFor1nClassAndNoNamespace()
    {
        $path = $this->getFullFixturePath('/src/ComponentNotNestedNoNS.php');

        $this->strategy->registerClassPath('ComponentNotNestedNoNS', $path);

        $this->assertClassIsInstantiable('ComponentNotNestedNoNS');
    }

    /**
     * Test registerClassPath method
     * for the class specification with one level of nesting
     * and namespace with one level of nesting
     */
    public function testFor1nClassAnd1nNamespace()
    {
        $path = $this->getFullFixturePath('/src/ComponentNotNestedWithNS.php');

        $this->strategy->registerClassPath('Dummy\ComponentNotNestedWithNS', $path);

        $this->assertClassIsInstantiable('Dummy\ComponentNotNestedWithNS');
    }

    /**
     * Test registerClassPath method
     * for the class specification with two levels of nesting
     * with no namespace
     */
    public function testFor2nClassAndNoNamespace()
    {
        $path = $this->getFullFixturePath('/src/Core/ComponentNestedNoNS.php');

        $this->strategy->registerClassPath('ComponentNestedNoNS', $path);

        $this->assertClassIsInstantiable('ComponentNestedNoNS');
    }

    /**
     * Test registerClassPath method
     * for the class specification with two levels of nesting
     * with namespace with one level of nesting
     */
    public function testFor2nClassAnd1nNamespace()
    {
        $path = $this->getFullFixturePath('/src/Core/ComponentNestedWithNS.php');

        $this->strategy->registerClassPath('Dummy\ComponentNestedWithNS', $path);

        $this->assertClassIsInstantiable('Dummy\ComponentNestedWithNS');
    }
}
