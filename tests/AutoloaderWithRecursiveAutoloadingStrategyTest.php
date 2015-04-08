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
 * AutoloaderWithRecursiveAutoloadingStrategyTest.
 * PHPUnit test class for Autloader with RecursiveAutoloadingStrategy class.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class AutoloaderWithRecursiveAutoloadingStrategyTest extends AbstractAutoloaderWithAutoloadingStrategyTest
{
    /**
     * Initialise strategy fixture.
     */
    protected function initialiseStrategy()
    {
        $this->strategy = new RecursiveAutoloadingStrategy();
    }

    /**
     * Test registered path doesn't exist.
     */
    public function testForNonexistentRegisteredPath()
    {
        $path = $this->getFullFixturePath('/nonexistent');

        $this->strategy->registerPath($path);

        $this->assertClassDoesNotExist('Class_0');
    }

    /**
     * Test registered path is empty directory.
     */
    public function testForEmptyRegisteredPath()
    {
        $path = $this->getFullFixturePath('/empty');

        $this->strategy->registerPath($path);

        $this->assertClassDoesNotExist('Class_0');
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

        $this->assertClassDoesNotExist('Class_2');
    }

    /**
     * Test class existent on higher level directory
     * than registered path is not found.
     */
    public function testForPathWithEmptyDirectory()
    {
        $path = $this->getFullFixturePath('/subdirectory-empty');

        $this->strategy->registerPath($path);

        $this->assertClassDoesNotExist('Class_2');
    }

    /**
     * Test registerPath method
     * for not nested directory path
     * and not nested class file
     * and class without namespace.
     */
    public function testFor0nPathAnd0nFileAndClassWithNoNamespace()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('Class_1');
    }

    /**
     * Test registerPath method
     * for not nested directory path
     * and not nested class file
     * and class with namespace.
     */
    public function testFor0nPathAnd0nFileAndClassWithNamespace()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('Dummy\Class_2');
    }

    /**
     * Test registerPath method
     * for not nested directory path
     * and class file with one level of nesting
     * and class without namespace.
     */
    public function testFor0nPathAnd1nFileAndClassWithNoNamespace()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('Class_1_1');
    }

    /**
     * Test registerPath method
     * for not nested directory path
     * and class file with one level of nesting
     * and class with namespace.
     */
    public function testFor0nPathAnd1nFileAndClassWithNamespace()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('Dummy\Class_1_2');
    }

    /**
     * Test registerPath method
     * for not nested directory path
     * and class file with two levels of nesting
     * and class without namespace.
     */
    public function testFor0nPathAnd2nFileAndClassWithNoNamespace()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('Class_1_1_1');
    }

    /**
     * Test registerPath method
     * for not nested directory path
     * and class file with two levels of nesting
     * and class with namespace.
     */
    public function testFor0nPathAnd2nFileAndClassWithNamespace()
    {
        $path = $this->getFullFixturePath('');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('Dummy\Class_1_1_2');
    }

    /**
     * Test registerPath method
     * for directory path file with two levels of nesting
     * and class file with two levels of nesting
     * and class without namespace.
     */
    public function testFor2nPathAnd2nFileAndClassWithNoNamespace()
    {
        $path = $this->getFullFixturePath('/subdirectory-1/subdirectory-1-1');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('Class_1_1_3');
    }

    /**
     * Test registerPath method
     * for directory path file with two levels of nesting
     * and class file with two levels of nesting
     * and class with namespace.
     */
    public function testFor2nPathAnd2nFileAndClassWithNamespace()
    {
        $path = $this->getFullFixturePath('/subdirectory-1/subdirectory-1-1');

        $this->strategy->registerPath($path);

        $this->assertClassIsInstantiable('Dummy\Class_1_1_4');
    }
}
