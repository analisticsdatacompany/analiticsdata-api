<?php

namespace App\Requests\User;

use App\Interfaces\IRequests;
use Exception;

class AuthRequest implements IRequests
{
    private $rows = [ "email", "password"];
    public $email;
    public $password;
    public function __construct($values = null)
    {
        if (!is_null($values) && is_array($values)) {
            foreach ($this->rows as $index => $row) {
                if (isset($values[strtolower($row)])) {
                    $this->$row = $values[strtolower($row)];
                }
            }
        }

        $this->isValid();
    }

    public function isValid(){
        if(empty($this->email) || is_null($this->email) || empty($this->password) || is_null($this->password)){
            throw new Exception("Usuario ou Senhas Invalidos.!");
        }

    }
}
