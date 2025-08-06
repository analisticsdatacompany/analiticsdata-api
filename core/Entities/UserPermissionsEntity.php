<?php
namespace App\Entities;



class UserPermissionsEntity{
    private $rows=["id","acl","description"];
    public $id;
    public $acl;
    public $description;
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