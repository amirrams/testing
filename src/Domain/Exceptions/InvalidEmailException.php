<?php

namespace Docfav\Domain\Exceptions;

use DomainException;
use Exception;

class InvalidEmailException extends DomainException
{

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
