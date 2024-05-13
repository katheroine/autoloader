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
 * PSR-4 autoloading strategy test.
 * PHPUnit test class for Psr4AutoloadingStrategy class.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 *
 * @runTestsInSeparateProcesses
 */
class Psr4AutoloadingStrategyTest extends AbstractAutoloadingStrategyTestCase
{
    private const PSR4_AUTOLOADING_STRATEGY_FULLY_QUALIFIED_CLASS_NAME = 'ExOrg\Autoloader\Psr4AutoloadingStrategy';

    /**
     * Test ExOrg\Autoloader\Psr4AutoloadingStrategy class exists.
     */
    public function testConstructorReturnsProperInstance()
    {
        $strategy = new Psr4AutoloadingStrategy();

        $this->assertInstanceOf(self::PSR4_AUTOLOADING_STRATEGY_FULLY_QUALIFIED_CLASS_NAME, $strategy);
    }

    /**
     * Test Psr0AutoloadingStrategy class implements AutoloadingStrategyInterface.
     */
    public function testImplementsAutoloadingStrategyInterace()
    {
        $strategy = new Psr4AutoloadingStrategy();

        $this->assertImplements($strategy, self::AUTOLOADING_STRATEGY_FULLY_QUALIFIED_INTERFACE_NAME);
    }

    /**
     * Test for nonexistent path.
     */
    public function testForNonexistentPath()
    {
        $path = $this->getFullFixturePath('/nonexistent');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassDoesNotExist('\Vendor\Package\Dummy\ComponentExistent');
    }

    /**
     * Test for empty path.
     */
    public function testForEmptyPath()
    {
        $path = $this->getFullFixturePath('/empty');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassDoesNotExist('\Vendor\Package\Dummy\ComponentExistent');
    }

    /**
     * Test for nonexistent class file.
     */
    public function testForNonexistentClassFile()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassDoesNotExist('\Vendor\Package\Dummy\ComponentNonexistent');
    }

    /**
     * Test for not registered namespace.
     */
    public function testForUnregisteredNamespace()
    {
        $this->assertClassDoesNotExist('\Vendor\Package\Dummy\ComponentExistent');
    }

    /**
     * Test for not registered namespace.
     */
    public function testForUnexistentNamespace()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Unexistent', $path);

        $this->assertClassDoesNotExist('\Vendor\Package\Dummy\ComponentExistent');
    }

    /**
     * Test for existent class file.
     */
    public function testForExistentClassFile()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\ComponentExistent');
    }

    /**
     * Test for namespace with backslash on the beginning.
     */
    public function testForNamespaceWithBackslashOnBeginning()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('\Vendor\Package', $path);

        $this->assertClassDoesNotExist('\Vendor\Package\Dummy\ComponentExistent');
    }

    /**
     * Test for namespace with backslash on the end.
     */
    public function testForNamespaceWithBackslashOnEnd()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package\\', $path);

        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\ComponentExistent');
    }

    /**
     * Test for namespace with backslash on the beginning and the end.
     */
    public function testForNamespaceWithBackslashOnBeginningAndEnd()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('\Vendor\Package\\', $path);

        $this->assertClassDoesNotExist('\Vendor\Package\Dummy\ComponentExistent');
    }

    /**
     * Test for fully qualified class name without backslash on the beginning.
     */
    public function testForFQClassNameWithoutBackslashOnBeginning()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassIsInstantiable('Vendor\Package\Dummy\ComponentExistent');
    }

    /**
     * Test for namespace registration with one level of nesting
     * and class specification with one level of nesting.
     */
    public function testFor1nNamespaceAnd1nClass()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\ComponentNotNested');
    }

    /**
     * Test for namespace registration with one level of nesting
     * and class specification with two levels of nesting.
     */
    public function testFor1nNamespaceAnd2nClass()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\Core\ComponentNested1');
    }

    /**
     * Test for namespace registration with two levels of nesting
     * and class specification with two levels of nesting.
     */
    public function testFor2nNamespaceAnd2nClass()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package\Dummy', $path);

        $this->assertClassDoesNotExist('\Vendor\Package\Dummy\Core\ComponentNested2');
    }

    /**
     * Test for the class with underscored namespace name.
     */
    public function testForClassWithUnderscoredNamespaceName()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\Underscored_Section\AdditionalComponent');
    }

    /**
     * Test for the class with underscored name.
     */
    public function testForClassWithUnderscoredName()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\Core\Underscored_Component');
    }

    /**
     * Test for many classes from the same namespace and directory path registered.
     */
    public function testManyClassesFromTheSameNamespace()
    {
        $path = $this->getFullFixturePath('/src');

        $this->strategy->registerNamespacePath('Vendor\Package', $path);

        $this->assertClassDoesNotExist('\Vendor\Package\Dummy\ComponentNonexistent');
        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\ComponentExistent');
        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\ComponentNotNested');
        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\Core\ComponentNested1');
        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\Core\ComponentNested2');
        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\Underscored_Section\AdditionalComponent');
        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\Core\Underscored_Component');
    }

    /**
     * Test for namespace registration with two directory paths registered
     * for the same namespace.
     */
    public function testForTwoPathsAndOneNamespace()
    {
        $path1 = $this->getFullFixturePath('/src');
        $path2 = $this->getFullFixturePath('/lib');

        $this->strategy->registerNamespacePath('Vendor\Package', $path1);
        $this->strategy->registerNamespacePath('Vendor\Package', $path2);

        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\ComponentA');
        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\ComponentB');
        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\Core\ComponentC');
        $this->assertClassIsInstantiable('\Vendor\Package\Dummy\Core\ComponentD');
    }

    /**
     * Test for the case from PRS-1 reference page
     * https://www.php-fig.org/psr/psr-4/#3-examples.
     * First example.
     */
    public function testFromReference1()
    {
        $path = $this->getFullFixturePath('/acme-log-writer/lib/');

        $this->strategy->registerNamespacePath('Acme\Log\Writer', $path);

        $this->assertClassIsInstantiable('\Acme\Log\Writer\File_Writer');
    }

    /**
     * Test for the case from PRS-1 reference page
     * https://www.php-fig.org/psr/psr-4/#3-examples.
     * Second example.
     */
    public function testFromReference2()
    {
        $path = $this->getFullFixturePath('/path/to/aura-web/src');

        $this->strategy->registerNamespacePath('Aura\Web', $path);

        $this->assertClassIsInstantiable('\Aura\Web\Response\Status');
    }

    /**
     * Test for the case from PRS-1 reference page
     * https://www.php-fig.org/psr/psr-4/#3-examples.
     * Third example.
     */
    public function testFromReference3()
    {
        $path = $this->getFullFixturePath('/vendor/Symfony/Core');

        $this->strategy->registerNamespacePath('Symfony\Core', $path);

        $this->assertClassIsInstantiable('\Symfony\Core\Request');
    }

    /**
     * Test for the case from PRS-1 reference page
     * https://www.php-fig.org/psr/psr-4/#3-examples.
     * Fourth example.
     */
    public function testFromReference4()
    {
        $path = $this->getFullFixturePath('/usr/includes/Zend');

        $this->strategy->registerNamespacePath('Zend', $path);

        $this->assertClassIsInstantiable('\Zend\Acl');
    }

    /**
     * Tests for all cases from PRS-1 reference page
     * https://www.php-fig.org/psr/psr-4/#3-examples.
     */
    public function testFromReferenceGrouped()
    {
        $path1 = $this->getFullFixturePath('/acme-log-writer/lib/');
        $path2 = $this->getFullFixturePath('/path/to/aura-web/src');
        $path3 = $this->getFullFixturePath('/vendor/Symfony/Core');
        $path4 = $this->getFullFixturePath('/usr/includes/Zend');

        $this->strategy->registerNamespacePath('Acme\Log\Writer', $path1);
        $this->strategy->registerNamespacePath('Aura\Web', $path2);
        $this->strategy->registerNamespacePath('Symfony\Core', $path3);
        $this->strategy->registerNamespacePath('Zend', $path4);

        $this->assertClassIsInstantiable('\Acme\Log\Writer\File_Writer');
        $this->assertClassIsInstantiable('\Aura\Web\Response\Status');
        $this->assertClassIsInstantiable('\Symfony\Core\Request');
        $this->assertClassIsInstantiable('\Zend\Acl');
    }

    /**
     * Provide autoloading strategy instance
     * against which the tests will be running.
     *
     * @return AutoloadingStrategyInterface
     */
    protected function provideStrategyIstance(): AutoloadingStrategyInterface
    {
        return new Psr4AutoloadingStrategy();
    }
}
