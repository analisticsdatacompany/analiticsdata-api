<?php

use App\Dao\DB;
use App\Libs\Json;
use App\Dao\Qr\QrDao;
use App\Libs\Response;
use App\Dao\Acl\AclDao;
use App\Dao\User\UserDao;
use AnaliticsCommons\Http;
use App\Libs\PasswordHash;
use App\Libs\ContentValues;
use App\Libs\StringBuilder;

require_once __DIR__ . "./../vendor/autoload.php";

Http::cors();


$userId = 1;
echo "<pre>";
try {
    if (AclDao::userHasAllPermissions(1, ['*'])->isTrue()) {
        // print_r(["<pre>",AclDao::getUserPermissions($userId)]);

        print_r([UserDao::authenticate("admin@example.com", "1")]);
    } else {
        echo "Acesso negado.";
    }
} catch (Exception $e) {
    print_r(["<pre>", $e]);
}
 





 /*
$pdo = DB::getInstance()->getConnection();
$stmt = $pdo->query("SELECT NOW()");
echo $stmt->fetchColumn();
 




$qrs = new QrDao();

// Criar novo QR
$id = $qrs->create();

// Buscar por ID
$qr = $qrs->getById($id);

 

// Listar todos
$todos = $qrs->listAll();

// Deletar
//$qrs->delete($id);
echo "<pre>";
var_dump($todos);

*/
/*
$userId = 5;

if (AclDao::userHasPermission($userId, 'read_users')) {
    echo "Usuário pode acessar usuários!";
} else {
    echo "Acesso negado.";
}


$hasAll = AclDao::userHasAllPermissions(5, ['read_users', 'edit_users']);
$hasAny = AclDao::userHasAnyPermission(5, ['read_users', 'delete_users']);
*/

/*

$l=new StringBuilder();
$l->add(9);

$l->add(9);
$l->add(9);
$l->add(9);
$l->add(9);
$l->add(9);
$l->add(9);
$l->add(9);
$l->add(9);
$l->add(9);
$l->add(9);
$l->add(9);

Response::json($l->toString());

*/
