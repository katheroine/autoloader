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
 * RecursiveAutoloadingStrategyTest.
 * PHPUnit test class for FixedAutoloadingStrategy class.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class RecursiveAutoloadingStrategyTest extends AbstractAutoloadingStrategyTest
{
    /**
     * Initialise strategy fixture.
     */
    protected function initialiseStrategy()
    {
        $this->strategy = new RecursiveAutoloadingStrategy();
    }

    /**
     * Test Exorg\Autoloader\RecursiveAutoloadingStrategy class exists.
     */
    public function testConstructorReturnsProperInstance()
    {
        $strategy = new RecursiveAutoloadingStrategy();

        $this->assertInstanceOf('Exorg\Autoloader\RecursiveAutoloadingStrategy', $strategy);
    }

    /**
     * Test FixedAutoloadingStrategy class implements AutoloadingStrategyInterface.
     */
    public function testImplementsAutoloadingStrategyInterace()
    {
        $strategy = new RecursiveAutoloadingStrategy();

        $this->assertImplements($strategy, 'Exorg\Autoloader\AutoloadingStrategyInterface');
    }

    /**
     * Test class not existent in registered path is not found.
     */
    public function testForClassNotExistentInRegisteredPath()
    {
        $path = $this->getFullFixturePath('/subdirectory-1/subdirectory-1-1');

        $this->strategy->registerPath($path);

        $this->assertClassDoesNotExist('Class_1_2_1');
    }

    /**
     * Test class existent on higher level directory
     * than registered path is not found.
     */
    public function testForClassExistentOnHigherLevelThanRegisteredPath()
    {
        $path = $this->getFullFixturePath('/subdirectory-1/subdirectory-1-1');

        $this->strategy->registerPath($path);

        $this->assertClassDoesNotExist('\Class_2');
    }

    /**
     * Test class existent on higher level directory
     * than registered path is not found.
     */
    public function testForPathWithEmptyDirectory()
    {
        $path = $this->getFullFixturePath('/subdirectory-empty');

        $this->strategy->registerPath($path);

        $this->assertClassDoesNotExist('\Class_2');
    }

    /**
     * Test registerPath method
     * for not nested directories searching
     * and class without namespace.
     */
    public function testForNotNestedPathAndClassWithNoNamespace()
    {
        $path = $this->getFullFixturePath('/subdirectory-1/subdirectory-1-1');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('\Class_1_1_1');
    }

    /**
     * Test registerPath method
     * for not nested directories searching
     * and class with namespace.
     */
    public function testForNotNestedPathAndClassWithNamespace()
    {
        $path = $this->getFullFixturePath('/subdirectory-1/subdirectory-1-1');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('\Dummy\Class_1_1_2');
    }

    /**
     * Test registerPath method
     * for nested directories searching
     * and class without namespace.
     */
    public function testForNestedPathAndClassWithNoNamespace()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('\Class_1');
    }

    /**
     * Test registerPath method
     * for nested directories searching
     * and class with namespace.
     */
    public function testForNestedPathAndClassWithNamespace()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('\Dummy\Class_2');
    }

    /**
     * Test registerPath method
     * for nested directories searching
     * for singly nested class file
     * and class without namespace.
     */
    public function testForNestedFilesAndSinglyNestedPathAndClassWithNoNamespace()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('Class_1_1');
    }

    /**
     * Test registerPath method
     * for nested directories searching
     * for singly nested class file
     * and class with namespace.
     */
    public function testForNestedFilesAndSinglyNestedPathAndClassWithNamespace()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('\Dummy\Class_1_2');
    }

    /**
     * Test registerPath method
     * for nested directories searching
     * for doubly nested class file
     * and class without namespace.
     */
    public function testForNestedFilesAndDoublyNestedPathAndClassWithNoNamespace()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('\Class_1_1_1');
    }

    /**
     * Test registerPath method
     * for nested directories searching
     * for doubly nested class file
     * and class with namespace.
     */
    public function testForNestedFilesAndDoublyNestedPathAndClassWithNamespace()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('\Dummy\Class_1_1_2');
    }
}
