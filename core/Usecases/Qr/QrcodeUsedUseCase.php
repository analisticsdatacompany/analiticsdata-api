<?php



namespace App\Usecases\Qr;

use App\Libs\Core;
use App\Dao\Qr\QrDao;
use App\Exceptions\QrCodeException;

class QrcodeUsedUseCase
{
    public function __construct() {}
    public function execute($qrcode)
    {
        //Core::dd(($qrcode));
        if(!QrDao::exists($qrcode)){
            throw new QrCodeException('Not Qr-code  '.$qrcode,400);
        }

      

        return QrDao::markAsUsed($qrcode);
    }
}
