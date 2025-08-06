<?php
namespace App\Entities\User;



class UserEntity{
    private $rows=["id","user_name","email","fk_group","created_at"];
    public $id;
    public $user_name;
    public $email;
    public $fk_group;
    public $created_at;
    
    public function __construct($values=null)
    {
        
        if(!is_null($values) && is_array($values)){
            foreach($this->rows as $index=>$row){
                if(isset($values[$row])){
                    $this->$row = $values[$row];
                }
            }
        }

    }


}