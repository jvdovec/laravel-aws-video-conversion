<?php

namespace App\Exceptions;

use Exception;

class WithAdditionalDataException extends Exception
{
    protected array $_data;

    public function __construct(string $message = '', array $data = [])
    {
        $this->_data = $data;
        parent::__construct($message);
    }

    public function getData(): array
    {
        return $this->_data;
    }
}
