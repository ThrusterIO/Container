<?php

namespace Thruster\Component\Container\Tests;

use Thruster\Component\Container\Container;
use Thruster\Component\Container\ContainerProviderInterface;

/**
 * Class ContainerTest
 *
 * @package Thruster\Component\Container\Tests
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Container
     */
    private $container;

    public function setUp()
    {
        $this->container = new Container();
    }

    /**
     * @expectedException \Thruster\Component\Container\Exception\NotFoundException
     * @expectedExceptionMessage Identifier "foo.bar" not found
     */
    public function testHasAndGetMethodOnEmpty()
    {
        $this->assertFalse($this->container->has('foo.bar'));

        $this->container->get('foo.bar');
    }

    public function testConstructor()
    {
        $value = function () {
            return 1;
        };

        $values = ['foo.bar' => $value];

        $this->container = new Container($values);

        $this->assertTrue($this->container->has('foo.bar'));
        $this->assertEquals(1, $this->container->get('foo.bar'));
    }

    public function testSetGetRemove()
    {
        $i = 1;

        $value = function () use ($i) {
            return $i++;
        };

        $this->assertFalse($this->container->has('foo.bar'));

        $this->container->set('foo.bar', $value);

        $this->assertTrue($this->container->has('foo.bar'));

        $this->assertEquals($i, $this->container->get('foo.bar'));
        $this->assertEquals($i, $this->container->get('foo.bar'));

        $this->container->remove('foo.bar');

        $this->assertFalse($this->container->has('foo.bar'));
    }

    public function testSetRemoveReset()
    {
        $i = 1;

        $value = function () use ($i) {
            return $i++;
        };

        $this->assertFalse($this->container->has('foo.bar'));

        $this->container->set('foo.bar', $value);

        $this->assertTrue($this->container->has('foo.bar'));

        $this->assertEquals($i, $this->container->get('foo.bar'));
        $this->assertEquals($i, $this->container->get('foo.bar'));

        $this->container->remove('foo.bar');

        $this->assertFalse($this->container->has('foo.bar'));

        $this->assertFalse($this->container->has('foo.bar'));

        $this->container->set('foo.bar', $value);

        $this->assertTrue($this->container->has('foo.bar'));

        $this->assertEquals($i, $this->container->get('foo.bar'));
        $this->assertEquals($i, $this->container->get('foo.bar'));
    }

    public function testOffset()
    {
        $i = 1;

        $value = function () use ($i) {
            return $i++;
        };

        $this->assertFalse(isset($this->container['foo.bar']));

        $this->container['foo.bar'] = $value;

        $this->assertTrue(isset($this->container['foo.bar']));

        $this->assertEquals($i, $this->container['foo.bar']);
        $this->assertEquals($i, $this->container['foo.bar']);

        unset($this->container['foo.bar']);

        $this->assertFalse(isset($this->container['foo.bar']));
    }

    public function testFactory()
    {
        $i = 1;

        $value = function () use (&$i) {
            return $i++;
        };

        $this->container->factory('foo.bar', $value);
        $this->assertEquals($i, $this->container->get('foo.bar'));
        $this->assertEquals($i, $this->container->get('foo.bar'));
    }

    /**
     * @expectedException \Thruster\Component\Container\Exception\IdentifierFrozenException
     * @expectedExceptionMessage Identifier "foo.bar" is frozen
     */
    public function testSetFrozen()
    {
        $value = function () {
        };

        $this->container->set('foo.bar', $value);
        $this->container->get('foo.bar');

        $this->container->set('foo.bar', $value);
    }

    /**
     * @expectedException \Thruster\Component\Container\Exception\IdentifierFrozenException
     * @expectedExceptionMessage Identifier "foo.bar" is frozen
     */
    public function testFactoryFrozen()
    {
        $value = function () {
        };

        $this->container->set('foo.bar', $value);
        $this->container->get('foo.bar');

        $this->container->factory('foo.bar', $value);
    }

    public function testAddProvider()
    {
        $provider = new class implements ContainerProviderInterface {
            public function register(Container $container)
            {
                $value = function () {
                    return 1;
                };

                $container->set('foo.bar', $value);
            }
        };

        $this->assertFalse($this->container->has('foo.bar'));

        $this->container->addProvider($provider);

        $this->assertTrue($this->container->has('foo.bar'));

        $this->assertEquals(1, $this->container->get('foo.bar'));
    }
}
