<?php


namespace App\Dao\Acl;

use PDO;
use Exception;
use App\Dao\DB;
use App\Entities\BooleanEntity;
use App\Exceptions\SqlException;
use App\Entities\UserPermissionsEntity;

class AclDao
{
    private static function conn(): PDO
    {
        return DB::getInstance()->getConnection();
    }

    /**
     * Verifica se o usuário possui uma permissão específica.
     */
    public static function userHasPermission(int $userId, string $aclName): BooleanEntity
    {
        try {
            $stmt = self::conn()->prepare("
                SELECT 1
                FROM users u
                JOIN `groups` g ON u.fk_group = g.id
                JOIN group_acls ga ON ga.fk_group = g.id
                JOIN acls a ON a.id = ga.fk_acls
                WHERE u.id = :user_id AND a.acl = :acl_name AND a.state = 1
                LIMIT 1
            ");

            $stmt->execute([
                'user_id' => $userId,
                'acl_name' => $aclName
            ]);

            return  new BooleanEntity((bool) $stmt->fetchColumn());
        } catch (Exception $e) {
            throw new  SqlException($e);
        }
    }


    public static function userHasAllPermissions(int $userId, array $aclNames): BooleanEntity
    {
        try {
            if (empty($aclNames)) {
                return new BooleanEntity(); // Retorna uma entidade falsa se não houver ACLs
            }

            // Gerar placeholders para a cláusula IN
            $inClause = implode(',', array_fill(0, count($aclNames), '?'));

            // Consulta SQL com placeholders dinâmicos
            $sql = "
            SELECT COUNT(DISTINCT a.acl)
            FROM users u
            JOIN `groups` g ON u.fk_group = g.id
            JOIN group_acls ga ON ga.fk_group = g.id
            JOIN acls a ON a.id = ga.fk_acls
            WHERE u.id = ? AND a.acl IN ($inClause) AND a.state = 1
        ";

            // Preparar a consulta
            $stmt = self::conn()->prepare($sql);

            // Mesclar os parâmetros de usuário e ACLs
            $params = array_merge([$userId], $aclNames);

            // Executar a consulta com os parâmetros corretamente passados
            $stmt->execute($params);

            // Obter o resultado da contagem de ACLs
            $count = (int) $stmt->fetchColumn();

            // Retorna verdadeiro se o número de ACLs for igual ao número de ACLs passadas
            return new BooleanEntity($count === count($aclNames));
        } catch (Exception $e) {
            // Lidar com exceções e lançar um erro mais específico
            throw new SqlException($e);
        }
    }


    public static function userHasAnyPermission(int $userId, array $aclNames): BooleanEntity
    {
        try {
            if (empty($aclNames)) {
                return new BooleanEntity();
            }

            $inClause = implode(',', array_fill(0, count($aclNames), '?'));
            $sql = "
                SELECT 1
                FROM users u
                JOIN `groups` g ON u.fk_group = g.id
                JOIN group_acls ga ON ga.fk_group = g.id
                JOIN acls a ON a.id = ga.fk_acls
                WHERE u.id = ? AND a.acl IN ($inClause) AND a.state = 1
                LIMIT 1
            ";

            $stmt = self::conn()->prepare($sql);
            $params = array_merge([$userId], $aclNames);
            $stmt->execute($params);

            return new BooleanEntity((bool) $stmt->fetchColumn());
        } catch (Exception $e) {
            throw new  SqlException($e);
        }
    }




    /**
     * Lista todas as permissões de um usuário.
     */
    public static function getUserPermissions(int $userId): array
    {
        try {   
            $stmt = self::conn()->prepare("
            SELECT a.acl,a.description,a.id,a.acl,g.name,g.description
            FROM users u
            JOIN `groups` g ON u.fk_group = g.id
            JOIN group_acls ga ON ga.fk_group = g.id
            JOIN acls a ON a.id = ga.fk_acls
            WHERE u.id = :user_id AND a.state = 1
        ");

            // Executando a consulta com o parâmetro 'user_id'
            $stmt->execute(['user_id' => $userId]);

            // Obtendo as permissões (acl) como um array
            $permissions = [];

            foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
                array_push($permissions,new UserPermissionsEntity($row));
            }

            // Verifica se o usuário tem permissões
            if ($permissions === false) {
                // Nenhuma permissão encontrada
                return [];
            }

            // Retorna as permissões
            return $permissions;
        } catch (Exception $e) {
            // Lança uma exceção customizada com a mensagem de erro
            throw new SqlException("Erro ao listar permissões do usuário: " . $e->getMessage(), 0, $e);
        }
    }
}
