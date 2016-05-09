<?php

namespace Thruster\Component\Container\Exception;

use Exception;
use Interop\Container\Exception\NotFoundException as NotFoundExceptionInterface;

/**
 * Class NotFoundException
 *
 * @package Thruster\Component\Container\Exception
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class NotFoundException extends Exception implements NotFoundExceptionInterface
{
    /**
     * @param string $id Identifier
     */
    public function __construct($id)
    {
        $message = sprintf(
            'Identifier "%s" not found',
            $id
        );

        parent::__construct($message);
    }
}
