<?php

use AnaliticsCommons\Http;
use App\Libs\Response;
use App\Usecases\Qr\QrcodeUseCase;

require_once __DIR__ . "./../../vendor/autoload.php";
Http::cors();

try {

    $qrcodeUseCase =  new QrcodeUseCase();
    $output =  [
        "qrcode" => $qrcodeUseCase->execute()
    ];
    Response::json($output);

} catch (Throwable $e) {
    Response::json($e->getMessage(),null, $e->getCode());
}