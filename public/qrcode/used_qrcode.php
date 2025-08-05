<?php

use App\Libs\Response;
use AnaliticsCommons\Http;
use App\Exceptions\QrCodeException;
use App\Libs\Core;
use App\Usecases\Qr\QrcodeUseCase;
use App\Usecases\Qr\QrcodeUsedUseCase;

require_once __DIR__ . "./../../vendor/autoload.php";
Http::cors();

try {
    $request=Http::all();

    
    if(!isset($request['QRCODE']) || strlen(trim($request['QRCODE']))==0){
        throw new QrCodeException("Qr-Code Invalid.",400);
    }

    $qrcodeUsedUseCase =  new QrcodeUsedUseCase();
    if ($qrcodeUsedUseCase->execute($request['QRCODE'])) {
        Response::json(null, null,201);
    }

    Response::jsonNoResult(400);
} catch (Throwable $e) {
    Response::json($e->getMessage(),null, $e->getCode());
}
