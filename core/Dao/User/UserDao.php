<?php

namespace App\Dao\User;

use PDO;
use Exception;
use App\Dao\DB;
use App\Libs\PasswordHash;
use App\Exceptions\SqlException;
use App\Entities\User\UserEntity;
 



class UserDao
{
    private static function conn(): PDO
    {
        return DB::getInstance()->getConnection();
    }


    // Função de autenticação
    public static function authenticate(string $email, string $password):UserEntity
    {

        try {
            $conn = self::conn();

            // Consulta SQL ajustada para o esquema de banco de dados fornecido
            $query = 'SELECT id, `name` as user_name , email, `password` as user_password, fk_group, created_at FROM users WHERE email = :email LIMIT 1';
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            // Verifica se o usuário foi encontrado
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                // Verifica se a senha fornecida é válida
                if (PasswordHash::Verify($password, $user['user_password'])) {
                    
                    return new UserEntity($user);
                }
            }
        } catch (Exception $e) {
            throw new  SqlException($e);
        }
    }
}
