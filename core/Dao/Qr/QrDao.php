<?php

namespace App\Dao\Qr;

use PDO;
use App\Dao\DB;
use App\Libs\Core;

class QrDao
{
    /**
     * Obtém conexão PDO usando singleton DB
     */
    private static function conn(): PDO
    {
        return DB::getInstance()->getConnection();
    }

    /**
     * Retorna o IP do cliente, com fallback seguro
     */
    private static function getAddress(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Gera UUID v4
     */
    private static function generateUuidV4(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * Verifica se o QR code existe
     */
    public static function exists(string $qrcode): bool
    {
        $stmt = self::conn()->prepare("SELECT COUNT(*) FROM qrs WHERE qrcode = :qrcode and created_used is null");
        $stmt->execute(['qrcode' => $qrcode]);


        return $stmt->fetchColumn() > 0;
    }

    /**
     * Verifica se o endereço IP já está cadastrado
     */
    public static function existsAddress(string $address): bool
    {
        $stmt = self::conn()->prepare("SELECT COUNT(*) FROM qrs WHERE address = :address");
        $stmt->execute(['address' => $address]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Atualiza o QR code de um endereço IP existente
     */
    public static function updateByAddress(): string
    {
        $address = self::getAddress();
        $qrcode = self::generateUuidV4();

      

        $stmt = self::conn()->prepare("
            UPDATE qrs 
            SET qrcode = :qrcode 
            WHERE address = :address
        ");
        $stmt->execute([
            'qrcode' => $qrcode,
            'address' => $address
        ]);

        return  $qrcode;
    }

    /**
     * Cria ou atualiza um QR code com base no endereço IP atual
     * @param string $qrcode
     * @return int ID do registro
     */
    public static function createOrUpdateByAddress(string $qrcode = ""): int
    {
        $address = self::getAddress();
        $qrcode = $qrcode ?: self::generateUuidV4();

        // Verifica se existe registro para o IP
        $stmt = self::conn()->prepare("SELECT id, created_used FROM qrs WHERE address = :address ORDER BY created_at DESC LIMIT 1");
        $stmt->execute(['address' => $address]);
        $record = $stmt->fetch();

        if ($record) {
            if ($record['created_used'] === null || $record['created_used'] === '') {
                throw new \Exception("QR Code para este IP ainda não foi usado. Não pode criar novo.");
            }

            // cria novo registro
            $stmt = self::conn()->prepare("
        INSERT INTO qrs (qrcode, created_at, address) 
        VALUES (:qrcode, NOW(), :address)
    ");
            $stmt->execute([
                'qrcode' => $qrcode,
                'address' => $address
            ]);
            return (int) self::conn()->lastInsertId();
        } else {
            // cria novo registro
            $stmt = self::conn()->prepare("
        INSERT INTO qrs (qrcode, created_at, address) 
        VALUES (:qrcode, NOW(), :address)
    ");
            $stmt->execute([
                'qrcode' => $qrcode,
                'address' => $address
            ]);
            return (int) self::conn()->lastInsertId();
        }
    }


    /**
     * Cria um novo QR code
     * @param string $qrcode
     * @throws \Exception
     */
    public static function create(): string
    {
        $qrcode = self::generateUuidV4();

        if (self::exists($qrcode)) {
            throw new \Exception("QR Code já existe.");
        }

        $address = self::getAddress();
        $stmt = self::conn()->prepare("
            INSERT INTO qrs (qrcode, created_at, address) 
            VALUES (:qrcode, NOW(), :address)
        ");
        $stmt->execute([
            'qrcode' => $qrcode,
            'address' => $address
        ]);
        return $qrcode;
    }

    /**
     * Busca um QR code pelo ID
     */
    public static function getById(int $id): ?array
    {
        $stmt = self::conn()->prepare("SELECT * FROM qrs WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }



    /**
     * Busca um QR code pelo IP
     */
    public static function getByAddress(): ?array
    {
        $ip =  self::getAddress();
        $stmt = self::conn()->prepare("SELECT * FROM qrs WHERE `address` = :ip and created_used is null");
        $stmt->execute(['ip' => $ip]);
        $result = $stmt->fetch();
        return $result ?: null;
    }




    /**
     * Marca um QR code como utilizado
     */
    public static function markAsUsed($qrcode)
    {
        $stmt = self::conn()->prepare("UPDATE qrs SET created_used = NOW() WHERE qrcode = :qrcode");
        return $stmt->execute(['qrcode' => $qrcode]);
    }

    /**
     * Deleta um QR code
     */
    public static function delete(int $id): bool
    {
        $stmt = self::conn()->prepare("DELETE FROM qrs WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Lista todos os QR codes
     */
    public static function listAll(): array
    {
        $stmt = self::conn()->query("SELECT * FROM qrs ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    /**
     * Busca o primeiro QR code não utilizado
     */
    public static function getOneUnused(): ?array
    {
        $stmt = self::conn()->prepare("
            SELECT * FROM qrs 
            WHERE created_used IS NULL OR created_used = '' 
            ORDER BY created_at ASC 
            LIMIT 1
        ");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
