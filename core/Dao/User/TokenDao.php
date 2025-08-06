<?php

namespace App\Dao\User;

use PDO;
use Exception;
use App\Dao\DB;
use App\Exceptions\SqlException;

class TokenDao
{
    private static function conn(): PDO
    {
        return DB::getInstance()->getConnection();
    }

    // Criar um novo token
    public static function create(string $token, int $userId, ?\DateTime $expired = null): bool
    {
        try {
            // Se não for passado, define uma data de expiração padrão (30 dias a partir de agora)
            if ($expired === null) {
                $expired = new \DateTime('+1 days');
            }

            $conn = self::conn();
            $query = 'INSERT INTO tokens (token, fk_user, expired) VALUES (:token, :user_id, :expired)';
            $stmt = $conn->prepare($query);

            // Formatar a data e atribuir a uma variável
            $expiredFormatted = $expired->format('Y-m-d H:i:s');

            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':expired', $expiredFormatted, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new SqlException($e);
        }
    }

    // Localizar um token por seu valor
    public static function findByUserToken(string $userId): ?array
    {
        try {
            $conn = self::conn();
            $query = 'SELECT id, token, fk_user, expired FROM tokens WHERE deleted is null and  fk_user = :userId LIMIT 1';
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }

            return null;
        } catch (Exception $e) {
            throw new SqlException($e);
        }
    }




    // Localizar um token por seu valor
    public static function findByToken(string $token): ?array
    {
        try {
            $conn = self::conn();
            $query = 'SELECT id, token, fk_user, expired FROM tokens WHERE  deleted is null and token = :token LIMIT 1';
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }

            return null;
        } catch (Exception $e) {
            throw new SqlException($e);
        }
    }
    public static function updateToken(string $token): bool
    {
        try {
            // Cria o novo objeto DateTime com 1 dia à frente
            $newExpirationDate = new \DateTime('+1 day');

            // Formata a data para 'Y-m-d H:i:s' (ex: 2025-08-07 12:00:00)
            $formattedExpirationDate = $newExpirationDate->format('Y-m-d H:i:s');

            // Conecta ao banco de dados
            $conn = self::conn();

            // SQL para atualizar a data de expiração do token
            $query = 'UPDATE tokens SET expired = :new_expiration WHERE token = :token';
            $stmt = $conn->prepare($query);

            // Faz o bind dos parâmetros
            $stmt->bindParam(':new_expiration', $formattedExpirationDate, PDO::PARAM_STR); // Agora é uma string formatada
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);

            // Executa a query
            return $stmt->execute();
        } catch (Exception $e) {
            // Caso ocorra um erro, lança a exceção personalizada
            throw new SqlException($e);
        }
    }


    // Finalizar o token, ou seja, alterar seu status para 0 se expirado
    public static function finalizeExpiredToken($token): bool
    {
        try {
            $conn = self::conn();
            $query = 'UPDATE tokens SET deleted=now() WHERE token=:token ';
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new SqlException($e);
        }
    }

    // Deletar um token pelo ID
    public static function delete(int $id): bool
    {
        try {
            $conn = self::conn();
            $query = 'DELETE FROM tokens WHERE id = :id';
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new SqlException($e);
        }
    }
}
