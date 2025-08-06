<?php

namespace App\Libs;

use Exception;

class Http
{
    private function __clone() {}
    private function __construct() {}


    static  function cors()
    {
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
            // you want to allow, and if so:
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                // may also be using PUT, PATCH, HEAD etc
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

            exit(0);
        }
    }


    static function bodyJson(){
        try {
            return json_decode(file_get_contents('php://input'));
        } catch (Exception $e) {
        }
        return [];
    }


    static function all()
    {
      
        $_inputs = [Http::bodyJson(),$_POST, $_GET, $_FILES, $_COOKIE, $_SERVER,getallheaders()];
        $_buffer = [];
        foreach ($_inputs as $index => $value) {
            if($value){
              
                foreach($value as $_index => $_value){
                    $_buffer[strtoupper($_index)] = $_value;
                }
               
            }
        }

        return $_buffer;
    }
}



