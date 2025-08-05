<?php



namespace App\Usecases\Qr;

use App\Dao\Qr\QrDao;

class QrcodeUseCase
{
    public function __construct() {}
    public function execute()
    {

        $resultQrData = QrDao::getByAddress();
        if (is_null($resultQrData)) {
            return QrDao::create();
        }

        return QrDao::updateByAddress();

    }
}
