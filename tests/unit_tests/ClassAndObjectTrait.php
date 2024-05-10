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
 * Class and object trait.
 * Answers questions about classe and object states and features.
 *
 * @package Autoloader
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-autoloader
 */
trait ClassAndObjectTrait
{
    /**
     * Assert class does not exist.
     *
     * @param string $class
     */
    protected static function assertClassDoesNotExist(string $class): void
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
    protected static function assertClassIsInstantiable(string $class): void
    {
        $object = new $class();

        parent::assertInstanceOf($class, $object);

        unset($object);
    }

    /**
     * Assert object class implements given interface.
     *
     * @param mixed  $object
     * @param string $interface
     */
    protected function assertImplements(mixed $object, string $interface): void
    {
        $implementedInterfaces = class_implements($object);
        $implementsInterface = in_array($interface, $implementedInterfaces);

        $this->assertTrue($implementsInterface);
    }
}
