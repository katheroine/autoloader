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
 *
 * @runTestsInSeparateProcesses
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

        $this->assertClassDoesNotExist('Dummy_ComponentExistent');
    }

    /**
     * Test for empty registered path.
     */
    public function testForEmptyPath()
    {
        $path = $this->getFullFixturePath('/empty');

        $this->strategy->registerPrefixPath('Dummy', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentExistent');
    }

    /**
     * Test for nonexistent class file.
     */
    public function testForNonexistentClassFile()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerPrefixPath('Dummy', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentNonexistent');
    }

    /**
     * Test for unregisterd path.
     */
    public function testForUnregisteredPrefix()
    {
        $this->assertClassDoesNotExist('Dummy_ComponentExistent');
    }

    /**
     * Test unexistent prefix.
     */
    public function testForUnexistentPrefix()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerPrefixPath('Nonexistent_', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentNotRegistered');
    }

    /**
     * Test for existent class file.
     */
    public function testForExistentClassFile()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerPrefixPath('Dummy', $path);

        $this->assertClassIsInstantiable('Dummy_ComponentExistent');
    }

    /**
     * Test for prefix (pseudonamespace) with underscore on the beginning.
     */
    public function testForPrefixWithUnderscoreOnBeginning()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerPrefixPath('_Dummy', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentExistent');
    }

    /**
     * Test for prefix (pseudonamespace) with underscore on the end.
     */
    public function testForPrefixWithUnderscoreOnEnd()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerPrefixPath('Dummy_', $path);

        $this->assertClassIsInstantiable('Dummy_ComponentExistent');
    }

    /**
     * Test for prefix (pseudonamespace) with underscore on the beginning and the end.
     */
    public function testForPrefixWithUnderscoreOnBeginningAndEnd()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerPrefixPath('_Dummy_', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentExistent');
    }

    /**
     * Test for prefixed class name with backslash on the beginning.
     */
    public function testForFQClassNameWithoutBackslashOnBeginning()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerPrefixPath('Dummy', $path);

        $this->assertClassIsInstantiable('\Dummy_ComponentExistent');
    }

    /**
     * Test for prefix registration with one level of nesting
     * and class specification with one level of nesting.
     */
    public function testFor1nPrefixAnd1nClass()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerPrefixPath('Dummy', $path);

        $this->assertClassIsInstantiable('Dummy_ComponentNotNested');
    }

    /**
     * Test for prefix registration with one level of nesting
     * and class specification with two levels of nesting.
     */
    public function testFor1nPrefixAnd2nClass()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerPrefixPath('Dummy', $path);

        $this->assertClassIsInstantiable('Dummy_Core_ComponentNested');
    }

    /**
     * Test for prefix registration with two levels of nesting
     * and class specification with two levels of nesting.
     */
    public function testFor2nPrefixAnd2nClass()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerPrefixPath('Dummy_Core', $path);

        $this->assertClassIsInstantiable('Dummy_Core_ComponentNested');
    }

    /**
     * Test for many classes from the same namespace and directory path registered.
     */
    public function testManyClassesFromTheSamePrefix()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerPrefixPath('Dummy', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentNonexistent');
        $this->assertClassIsInstantiable('Dummy_ComponentExistent');
        $this->assertClassIsInstantiable('Dummy_ComponentNotNested');
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
