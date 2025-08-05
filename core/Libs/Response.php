<?php

namespace  App\Libs;

class Response
{


    public static function json($data = null, $bundle = [],$state = 200)
    {
        http_response_code($state); // Usar função nativa para setar o state
        header('Content-Type: application/json; charset=utf-8');

        $error = ($state < 200 || $state >= 300); // Melhor para capturar state tipo 4xx e 5xx corretamente

        $response = [
            'state' => $state,
            'error' => $error,
            'result' => $data,
            'bundle' => $bundle
        ];

        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit; // Usa exit no lugar de die (é mais claro para PHP moderno)
    }

     public static function jsonNoResult($state = 200,$bundle = [])
    {
        http_response_code($state); // Usar função nativa para setar o state
        header('Content-Type: application/json; charset=utf-8');

        $error = ($state < 200 || $state >= 300); // Melhor para capturar state tipo 4xx e 5xx corretamente

        $response = [
            'state' => $state,
            'error' => $error,
            'result' => null,
            'bundle' => $bundle
        ];

        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit; // Usa exit no lugar de die (é mais claro para PHP moderno)
    }

}
