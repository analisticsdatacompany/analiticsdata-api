<?php
namespace App\Entities;

class BooleanEntity{
    public  $value;

    public function __construct($value=false)
    {
        $this->value = $value;
    }

    public function isTrue(){
        return $this->value;
    }
}