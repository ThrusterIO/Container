<?php

namespace Thruster\Component\Container;

/**
 * Trait ContainerAwareTrait
 *
 * @package Thruster\Component\Container
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
trait ContainerAwareTrait
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @return Container
     */
    public function getContainer() : Container
    {
        return $this->container;
    }

    /**
     * @param Container $container
     *
     * @return $this
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }
}
