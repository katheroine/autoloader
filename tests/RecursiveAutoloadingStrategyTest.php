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
 * Recursive autoloading strategy test.
 * PHPUnit test class for RecursiveAutoloadingStrategy class.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
class RecursiveAutoloadingStrategyTest extends AbstractAutoloadingStrategyTestCase
{
    /**
     * Initialise strategy fixture.
     */
    protected function initialiseStrategy(): void
    {
        $this->strategy = new RecursiveAutoloadingStrategy();
    }

    /**
     * Test ExOrg\Autoloader\RecursiveAutoloadingStrategy class exists.
     */
    public function testConstructorReturnsProperInstance()
    {
        $strategy = new RecursiveAutoloadingStrategy();

        $this->assertInstanceOf('ExOrg\Autoloader\RecursiveAutoloadingStrategy', $strategy);
    }

    /**
     * Test FixedAutoloadingStrategy class implements AutoloadingStrategyInterface.
     */
    public function testImplementsAutoloadingStrategyInterace()
    {
        $strategy = new RecursiveAutoloadingStrategy();

        $this->assertImplements($strategy, 'ExOrg\Autoloader\AutoloadingStrategyInterface');
    }

    /**
     * Test for nonexistent registered path.
     */
    public function testForNonexistentPath()
    {
        $path = $this->getFullFixturePath('/nonexistent');

        $this->strategy->registerPath($path);

        $this->assertClassDoesNotExist('Class_0');
    }

    /**
     * Test for empty registered path.
     */
    public function testForEmptyPath()
    {
        $path = $this->getFullFixturePath('/empty');

        $this->strategy->registerPath($path);

        $this->assertClassDoesNotExist('Class_0');
    }

    /**
     * Test for the class not existent in registered path is not found.
     */
    public function testForNonexistentClass()
    {
        $path = $this->getFullFixturePath('/subdirectory-1/subdirectory-1-1');

        $this->strategy->registerPath($path);

        $this->assertClassDoesNotExist('Class_Nonexistent');
    }

    /**
     * Test for the class existent on higher level directory
     * than registered path is not found.
     */
    public function testForClassExistentOnHigherLevelThanRegisteredPath()
    {
        $path = $this->getFullFixturePath('/subdirectory-1/subdirectory-1-1');

        $this->strategy->registerPath($path);

        $this->assertClassDoesNotExist('Class_2');
    }

    /**
     * Test for the class existent on higher level directory
     * than registered path is not found.
     */
    public function testForPathWithEmptyDirectory()
    {
        $path = $this->getFullFixturePath('/subdirectory-empty');

        $this->strategy->registerPath($path);

        $this->assertClassDoesNotExist('Class_2');
    }

    /**
     * Test for not nested directory path
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
     * Test for not nested directory path
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
     * Test for not nested directory path
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
     * Test for not nested directory path
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
     * Test for not nested directory path
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
     * Test for not nested directory path
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
     * Test for directory path file with two levels of nesting
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
     * Test for directory path file with two levels of nesting
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
