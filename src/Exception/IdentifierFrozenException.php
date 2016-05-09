<?php

namespace Thruster\Component\Container\Exception;

use Exception;

/**
 * Class IdentifierFrozenException
 *
 * @package Thruster\Component\Container\Exception
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class IdentifierFrozenException extends Exception
{
    /**
     * @param string $id Identifier
     */
    public function __construct($id)
    {
        $message = sprintf(
            'Identifier "%s" is frozen',
            $id
        );

        parent::__construct($message);
    }
}
