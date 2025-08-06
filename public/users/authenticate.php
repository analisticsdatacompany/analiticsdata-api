<?php

use App\Dao\Acl\AclDao;
use App\Libs\Jwt;
use App\Libs\Http;
use App\Libs\Response;
use App\Dao\User\UserDao;

use App\Dao\User\TokenDao;
use App\Libs\Clock;
use App\Requests\User\AuthRequest;
use App\OutPut\User\AuthenticateOutPut;

require_once __DIR__ . "./../../vendor/autoload.php";

try {
    $config = require __DIR__ . '/../../core/config.php';
    Http::cors();
    $request = new AuthRequest(Http::all());
    $use = UserDao::authenticate($request->email, $request->password);
    if(is_null($use)) throw new Exception("Usuario ou Senhas Invalidas",400);
    $token = Jwt::encode(["id" => $use->id, "email" => $use->email, "unique" => uniqid()], $config['appkey']);
    TokenDao::create($token, $use->id);
    $data = [
        "id" => $use->id,
        "name" => $use->user_name,
        "email" => $use->email,
        "group" => $use->fk_group,
        "created_at" => $use->created_at,
        "token" => $token,
        "acls"=>AclDao::getUserPermissions($use->id)
    ];
    $output =  new AuthenticateOutPut($data);
    Response::json($output);
} catch (Exception $e) {
    Response::json([$e->getMessage()], null, $e->getCode());
}
