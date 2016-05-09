<?php

namespace Thruster\Component\Container;

/**
 * Interface ContainerProviderInterface
 *
 * @package Thruster\Component\Container
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
interface ContainerProviderInterface
{
    /**
     * @param Container $container
     */
    public function register(Container $container);
}
