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
 * AutoloadingProcessTestCase.
 * PHPUnit test class for all autoloading processes.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
abstract class AutoloadingProcessTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Assert class exist.
     *
     * @param string $class
     */
    protected static function assertClassExists($class)
    {
        $classIncluded = class_exists($class);

        parent::assertFalse($classIncluded);
    }

    /**
     * Assert there is possibility of creating an instance
     * of the given class.
     *
     * @param string $class
     */
    protected static function assertClassIsInstantiable($class)
    {
        $object = new $class();

        parent::assertInstanceOf($class, $object);

        unset($object);
    }
}
