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
class AutoloaderWithFixedAutoloadingStrategyTest extends AutoloaderWithAutoloadingStrategyTest
{
    /**
     * Initialise strategy fixture.
     */
    protected function initialiseStrategy()
    {
        $this->strategy = new FixedAutoloadingStrategy();
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

        $object = new \ComponentNotNestedNoNS();

        $this->assertInstanceOf('\ComponentNotNestedNoNS', $object);
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

        $object = new \Dummy\ComponentNotNestedWithNS();

        $this->assertInstanceOf('\Dummy\ComponentNotNestedWithNS', $object);
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

        $object = new \ComponentNestedNoNS();

        $this->assertInstanceOf('\ComponentNestedNoNS', $object);
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

        $object = new \Dummy\ComponentNestedWithNS();

        $this->assertInstanceOf('\Dummy\ComponentNestedWithNS', $object);
    }
}
