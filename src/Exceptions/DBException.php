<?php
declare(strict_types=1);

namespace PaymentApi\Exceptions;

use Exception;
use AllowDynamicProperties;

#[AllowDynamicProperties] class DBException extends Exception
{

    public function __construct($message = 'Database Exception', $code = 0, Exception $previous = null, $customMessage = null, $customCode = null)
    {
        parent::__construct($message, $code, $previous);
        $this->customMessage = $customMessage;
        $this->customCode = $customCode;
    }
}
