<?php

namespace App\Services;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

class QrCodeService
{

    protected $builder;

    public function __construct(BuilderInterface $builder)
    {
        $this->builder=$builder;
    }

    public function qrcode(string $argument, int $id): string
    {
        $url = 'test.com/';

        $result = $this->builder
            ->data($url.$argument)
            ->size(400)
            ->margin(10)
            ->labelText('Activer/Désactiver le compteur')
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->build();

        $namePng = $id.'.png'; //ici mettre l'id du projet

        $result->saveToFile((\dirname(__DIR__, 2).'/public/assets/qr-code/'.$namePng)); //sauvegarder l'image

        return $result->getDataUri();
    }

}