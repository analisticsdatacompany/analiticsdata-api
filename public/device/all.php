<?php

use App\Libs\Http;
use App\Libs\Response;
use App\Midleware\Authenticate;

require_once __DIR__ . "./../../vendor/autoload.php";

try {
    $config = require __DIR__ . '/../../core/config.php';
    Http::cors();
    $request= Http::all();
    Authenticate::Init($config,$request,['get-devices']);
    $data = [
        $request
    ];
    $output = $data;
    Response::json($output);
} catch (Exception $e) {
    Response::json([$e->getMessage()], null, $e->getCode());
}
