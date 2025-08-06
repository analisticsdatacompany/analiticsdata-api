<?php

use App\Libs\Http;
use App\Libs\Response;
use App\Requests\User\AuthRequest;

require_once __DIR__ . "./../../vendor/autoload.php";

try {
    Http::cors();
    Response::json(new AuthRequest(Http::all()));
} catch (Exception $e) {
    Response::json([$e->getMessage()], null, $e->getCode());
}
