<?php

namespace App\OutPut\User;


class AuthenticateOutPut
{

    private $rows = ["id", "name", "email", "group", "created_at","token","acls"];
    public $id;
    public $name;
    public $email;
    public $group;
    public $created_at;
    public $token;
    public $acls=[];

    public function __construct($values = null)
    {

        if (!is_null($values) && is_array($values)) {
            foreach ($this->rows as $index => $row) {
                if (isset($values[$row])) {
                    $this->$row = $values[$row];
                }
            }
        }
    }
}
