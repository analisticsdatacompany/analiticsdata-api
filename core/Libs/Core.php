<?php

namespace App\Libs;

use Exception;

class Core
{
    // Prevenir clonagem da classe
    private function __clone() {}

    // Prevenir instanciamento direto
    private function __construct() {}

    /**
     * Carregar variáveis de ambiente de um arquivo .env
     * 
     * @param string $caminho Caminho para o arquivo .env
     * 
     * @throws \Exception Se o arquivo .env não for encontrado
     */
    public static function env($caminho = ".env")
    {
        if (!file_exists($caminho)) {
            throw new \Exception("Arquivo .env não encontrado.");
        }

        $linhas = file($caminho, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($linhas as $linha) {
            if (strpos($linha, '#') === 0 || empty($linha)) {
                continue; // Skip comments and empty lines
            }

            // Split the line into key and value, handling potential errors
            $parts = explode('=', $linha, 2);

            // Check if there are exactly 2 parts (key and value)
            if (count($parts) == 2) {
                $chave = trim($parts[0]);
                $valor = trim($parts[1]);

                // Ensure both key and value are non-empty before calling putenv
                if (!empty($chave) && !empty($valor)) {
                    putenv("$chave=$valor");
                }
            }
        }
    }

    /**
     * Filtra variáveis de ambiente com base no prefixo fornecido.
     *
     * @param array $lista O array de variáveis (exemplo: $_SERVER).
     * @param string $prefixo O prefixo a ser procurado (exemplo: 'DB_DEV_').
     * @return array O array filtrado com as variáveis que começam com o prefixo.
     */
    public static function filterByPrefix(array $lista, string $prefixo): array
    {
        // Filtra variáveis com o prefixo fornecido
        return array_filter($lista, function ($key) use ($prefixo) {
            return strpos($key, $prefixo) === 0;
        }, ARRAY_FILTER_USE_KEY);
    }

   


    public  static  function dd($obj)
    {
        echo "<pre>";
        print_r($obj);
        die;
    }
}