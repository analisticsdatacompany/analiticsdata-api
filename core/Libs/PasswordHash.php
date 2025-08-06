<?php

namespace App\Libs;

use Exception;

//define("PASSWORD_DEFAULT","AAAA22125325");

class PasswordHash
{
    private function __construct() {}
    private function __clone() {}
    public static function Create($password,$appkey="123")
    {
        return password_hash($password, $appkey);
    }

    public static function Verify($password, $passwordHash)
    {
        $hash = $passwordHash;
        if (password_verify($password, $hash)) {
            return true;
        } else {
            return false;
        }
    }
}
