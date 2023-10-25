<?php
declare(strict_types=1);

namespace PaymentApi\Exceptions;

use Exception;

class ValidationException extends Exception
{
    protected mixed $validationErrors;

    public function __construct($message = 'Validation Failed', $code = 400, Exception $previous = null, $validationErrors = [])
    {
        parent::__construct($message, $code, $previous);
        $this->validationErrors = $validationErrors;
    }

    public function getValidationErrors()
    {
        return $this->validationErrors;
    }
}
