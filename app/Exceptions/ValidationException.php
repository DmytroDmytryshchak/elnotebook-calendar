<?php

class ValidationException extends RuntimeException
{
    private $errors;

    public function __construct($errors)
    {
        parent::__construct('Validation failed.');
        $this->errors = $errors;
    }

    public function errors()
    {
        return $this->errors;
    }
}