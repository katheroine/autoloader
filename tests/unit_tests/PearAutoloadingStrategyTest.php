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
     * Test for nonexistent path.
     */
    public function testForNonexistentPath()
    {
        $path = $this->getFullFixturePath('/nonexistent');

        $this->strategy->registerPseudonamespacePath('Dummy', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentExistent');
    }

    /**
     * Test for empty path.
     */
    public function testForEmptyPath()
    {
        $path = $this->getFullFixturePath('/empty');

        $this->strategy->registerPseudonamespacePath('Dummy', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentExistent');
    }

    /**
     * Test for nonexistent class file.
     */
    public function testForNonexistentClassFile()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerPseudonamespacePath('Dummy', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentNonexistent');
    }

    /**
     * Test for not registerd pseudo-namespace.
     */
    public function testForUnregisteredPseudonamespace()
    {
        $this->assertClassDoesNotExist('Dummy_ComponentExistent');
    }

    /**
     * Test nonexistent pseudo-namespace.
     */
    public function testForNonexistentPseudonamespace()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerPseudonamespacePath('Nonexistent', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentNotRegistered');
    }

    /**
     * Test for existent class file.
     */
    public function testForExistentClassFile()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerPseudonamespacePath('Dummy', $path);

        $this->assertClassIsInstantiable('Dummy_ComponentExistent');
    }

    /**
     * Test for pseudo-namespace with underscore on the beginning.
     */
    public function testForPseudonamespaceWithUnderscoreOnBeginning()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerPseudonamespacePath('_Dummy', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentExistent');
    }

    /**
     * Test for pseudo-namespace with underscore on the end.
     */
    public function testForPseudonamespaceWithUnderscoreOnEnd()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerPseudonamespacePath('Dummy_', $path);

        $this->assertClassIsInstantiable('Dummy_ComponentExistent');
    }

    /**
     * Test for pseudo-namespace with underscore on the beginning and the end.
     */
    public function testForPseudonamespaceWithUnderscoreOnBeginningAndEnd()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerPseudonamespacePath('_Dummy_', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentExistent');
    }

    /**
     * Test for pseudo-namespaceed class name with backslash on the beginning.
     */
    public function testForFQClassNameWithoutBackslashOnBeginning()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerPseudonamespacePath('Dummy', $path);

        $this->assertClassIsInstantiable('\Dummy_ComponentExistent');
    }

    /**
     * Test for pseudo-namespace registration with one level of nesting
     * and class specification with one level of nesting.
     */
    public function testFor1nPseudonamespaceAnd1nClass()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerPseudonamespacePath('Dummy', $path);

        $this->assertClassIsInstantiable('Dummy_ComponentNotNested');
    }

    /**
     * Test for pseudo-namespace registration with one level of nesting
     * and class specification with two levels of nesting.
     */
    public function testFor1nPseudonamespaceAnd2nClass()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerPseudonamespacePath('Dummy', $path);

        $this->assertClassIsInstantiable('Dummy_Core_ComponentNested');
    }

    /**
     * Test for pseudo-namespace registration with two levels of nesting
     * and class specification with two levels of nesting.
     */
    public function testFor2nPseudonamespaceAnd2nClass()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerPseudonamespacePath('Dummy_Core', $path);

        $this->assertClassIsInstantiable('Dummy_Core_ComponentNested');
    }

    /**
     * Test for many classes from the same namespace and directory path registered.
     */
    public function testManyClassesFromTheSamePseudonamespace()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerPseudonamespacePath('Dummy', $path);

        $this->assertClassDoesNotExist('Dummy_ComponentNonexistent');
        $this->assertClassIsInstantiable('Dummy_ComponentExistent');
        $this->assertClassIsInstantiable('Dummy_ComponentNotNested');
        $this->assertClassIsInstantiable('Dummy_Core_ComponentNested');
    }

    /**
     * Test for pseudo-namespace registration with two directory paths registered
     * for the same pseudo-namespace.
     */
    public function testForTwoPathsAndOnePseudonamespace()
    {
        $path1 = $this->getFullFixturePath('/src');
        $path2 = $this->getFullFixturePath('/lib');

        $this->strategy->registerPseudonamespacePath('Dummy', $path1);
        $this->strategy->registerPseudonamespacePath('Dummy', $path2);

        $this->assertClassIsInstantiable('Dummy_ComponentA');
        $this->assertClassIsInstantiable('Dummy_ComponentB');
        $this->assertClassIsInstantiable('Dummy_Core_ComponentC');
        $this->assertClassIsInstantiable('Dummy_Core_ComponentD');
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
