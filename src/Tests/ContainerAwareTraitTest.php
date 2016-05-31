<?php

namespace Thruster\Component\Container\Tests;

use Thruster\Component\Container\Container;
use Thruster\Component\Container\ContainerAwareTrait;

/**
 * Class ContainerAwareTraitTest
 *
 * @package Thruster\Component\Container\Tests
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class ContainerAwareTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testTrait()
    {
        $container = new Container();

        $class = new class {
            use ContainerAwareTrait;
        };

        $class->setContainer($container);
        $this->assertEquals($container, $class->getContainer());
    }
}
