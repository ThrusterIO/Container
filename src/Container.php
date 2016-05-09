<?php

namespace Thruster\Component\Container;

use Interop\Container\ContainerInterface;
use Thruster\Component\Container\Exception\IdentifierFrozenException;
use Thruster\Component\Container\Exception\NotFoundException;

/**
 * Class Container
 *
 * @package Thruster\Component\Container
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class Container implements \ArrayAccess, ContainerInterface
{
    /**
     * @var array
     */
    private $values;

    /**
     * @var \SplObjectStorage
     */
    private $factories;

    /**
     * @var array
     */
    private $frozen;

    /**
     * @var array
     */
    private $raw;

    public function __construct(array $values = [])
    {
        $this->values     = [];
        $this->frozen     = [];
        $this->raw        = [];
        $this->factories  = [];

        foreach ($values as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @inheritDoc
     */
    public function has($id) : bool
    {
        return isset($this->raw[$id]);
    }

    /**
     * @inheritDoc
     */
    public function get($id)
    {
        if (false === $this->has($id)) {
            throw new NotFoundException($id);
        }

        if (isset($this->values[$id])) {
            return $this->values[$id];
        }

        if (isset($this->factories[$id])) {
            return $this->raw[$id]($this);
        }

        $raw                        = $this->raw[$id];
        $value = $this->values[$id] = $raw($this);

        $this->frozen[$id] = true;

        return $value;
    }

    public function set($id, callable $value) : self
    {
        if (isset($this->frozen[$id])) {
            throw new IdentifierFrozenException($id);
        }

        $this->raw[$id]  = $value;

        return $this;
    }

    public function factory($id, callable $value) : self
    {
        if (isset($this->frozen[$id])) {
            throw new IdentifierFrozenException($id);
        }

        $this->factories[$id] = true;
        $this->raw[$id]       = $value;

        return $this;
    }

    public function remove($id) : self
    {
        unset(
            $this->raw[$id],
            $this->values[$id],
            $this->frozen[$id],
            $this->factories[$id]
        );

        return $this;
    }

    public function addProvider(ContainerProviderInterface $provider) : self
    {
        $provider->register($this);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset) : bool
    {
        return $this->has($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }
}
