<?php


namespace App\Exceptions;

use Exception;

class SqlException extends Exception
{
    protected $details;

    public function __construct(string $message = "Erro ao executar sql.", int $code = 500, $details = null)
    {
        parent::__construct($message, $code);
        $this->details = $details;
    }

    public function getDetails()
    {
        return $this->details;
    }
}
