<?php

namespace App\Midleware;

use App\Dao\Acl\AclDao;
use App\Dao\User\TokenDao;
use App\Libs\Clock;
use App\Libs\Jwt;
use Exception;

class Authenticate{
    public static function Init($settings,$request,$permissions=[],$mult=false){
       
        if(!isset($request['jwt-authenticate'])){
            throw new Exception("Token Invalido..!",401);
        }
        $token=$request['jwt-authenticate'];
        $decode = Jwt::decode($token,$settings['appkey']);
        $tokenData=TokenDao::findByToken($token);
        $dif =  Clock::calculateDateDifference(date("Y-m-d H:m:s"),$tokenData['expired']);
        if($settings['expired_token_dias'] > $dif['days']){
              throw new Exception("Token Invalido Expirado..!",401);
        }
        $acls= AclDao::getUserPermissions($decode['id']);
        if(is_null($acls) || (is_array($acls) && count($acls)==0))  throw new Exception("Não tem Acesso aos  Recursos do Sitema  ..!",401);
        
        foreach($acls as $index=>$acl){
            if($acl->acl=='*') break;
            if(!$mult){
                if(!AclDao::userHasAnyPermission($decode['id'],$permissions)->isTrue()){
                     throw new Exception("Acesso ao Recurso  Invalido..!",401);
                }
            }else{
                if(!AclDao::userHasAllPermissions($decode['id'],$permissions)->isTrue()){
                     throw new Exception("Acesso ao Recurso  Invalido..!",401);
                }
            }
          
        }
        TokenDao::updateToken($token);
        return $decode;
     }
}