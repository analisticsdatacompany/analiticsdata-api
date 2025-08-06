<?php

namespace App\Libs;

class Jwt
{
    private static function base64url_encode($data)
    {
        return str_replace(['+','/','='], ['-','_',''], base64_encode($data));
    }
 
    private static function base64_decode_url($string) 
    {
        return base64_decode(str_replace(['-','_'], ['+','/'], $string));
    }
 
    // retorna JWT
    public static function encode(array $payload, string $secret): string
    {
     
        $header = json_encode([
            "alg" => "HS256",
            "typ" => "JWT"
        ]);
 
        $payload = json_encode($payload);
     
        $header_payload = static::base64url_encode($header) . '.'. 
                            static::base64url_encode($payload);
 
        $signature = hash_hmac('sha256', $header_payload, $secret, true);
         
        return 
            static::base64url_encode($header) . '.' .
            static::base64url_encode($payload) . '.' .
            static::base64url_encode($signature);
    }
 
    // retorna payload em formato array, ou lança um Exception
  public static function decode(string $token, string $secret): array
{
    // Split token into 3 parts
    $token = explode('.', $token);

    // Check if token is in the correct format
    if (count($token) !== 3) {
        throw new \Exception("Invalid token format.");
    }

    // Decode each part (header, payload, signature)
    $header = static::base64_decode_url($token[0]);
    $payload = static::base64_decode_url($token[1]);
    $signature = static::base64_decode_url($token[2]);
    
    // Check for null before using str_replace (if applicable)
    $header = $header ?? '';  // Default to empty string if null
    $payload = $payload ?? '';
    $signature = $signature ?? '';

    $header_payload = $token[0] . '.' . $token[1];

    // Validate the signature
    if (hash_hmac('sha256', $header_payload, $secret, true) !== $signature) {
        throw new \Exception("Invalid signature.");
    }

    return json_decode($payload, true);
}

 
}