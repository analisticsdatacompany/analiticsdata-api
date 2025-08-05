<?php


namespace App\Dao\Acl;

use PDO;
use App\Dao\DB;

class AclDao
{
    private static function conn(): PDO
    {
        return DB::getInstance()->getConnection();
    }

    /**
     * Verifica se o usuário possui uma permissão específica.
     */
    public static function userHasPermission(int $userId, string $aclName): bool
    {
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

        return (bool) $stmt->fetchColumn();
    }


    public static function userHasAllPermissions(int $userId, array $aclNames): bool
    {
        if (empty($aclNames)) {
            return false;
        }

        $inClause = implode(',', array_fill(0, count($aclNames), '?'));

        $sql = "
        SELECT COUNT(DISTINCT a.acl)
        FROM users u
        JOIN `groups` g ON u.fk_group = g.id
        JOIN group_acls ga ON ga.fk_group = g.id
        JOIN acls a ON a.id = ga.fk_acls
        WHERE u.id = ? AND a.acl IN ($inClause) AND a.state = 1
    ";

        $stmt = self::conn()->prepare($sql);
        $params = array_merge([$userId], $aclNames);
        $stmt->execute($params);

        $count = (int) $stmt->fetchColumn();
        return $count === count($aclNames);
    }


    public static function userHasAnyPermission(int $userId, array $aclNames): bool
    {
        if (empty($aclNames)) {
            return false;
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

        return (bool) $stmt->fetchColumn();
    }


    /**
     * Lista todas as permissões de um usuário.
     */
    public static function getUserPermissions(int $userId): array
    {
        $stmt = self::conn()->prepare("
            SELECT a.acl
            FROM users u
            JOIN groups g ON u.fk_group = g.id
            JOIN group_acls ga ON ga.fk_group = g.id
            JOIN acls a ON a.id = ga.fk_acls
            WHERE u.id = :user_id AND a.state = 1
        ");

        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
