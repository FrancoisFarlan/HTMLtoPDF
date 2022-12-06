<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Services\QrCodeService;
use Endroid\QrCode\Builder\BuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Snappy\Pdf;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;


class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_main")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }

    /**
     * @Route("/generate", name="generate")
     */
    public function generatePdf(Request $request, Pdf $pdf, QrCodeService $qrCodeService) :PdfResponse
    {
//        Generation d'un PDF à partir d'un template HTML : Le fichier PDF est directement téléchargé sur le PC
        $nom = "FARLAN";
        $prenom = "François";
        $societe = "Google";

        $url = $request->getSchemeAndHttpHost().$this->generateUrl('app_main');
        $id = 5;
        $qrCode = $qrCodeService->qrcode($url, $id);

        $html = $this->renderView('main/template.html.twig', [
            'nom'  => $nom,
            'prenom' => $prenom,
            'societe' =>$societe,
            'qrCode' => $qrCode
        ]);

        return new PdfResponse(
            $pdf->getOutputFromHtml($html),
            'file1.pdf'
        );


    }

    /**
     * @Route("/generateQr", name="/generateQr")
     */
    public function generateQr(Request $request, QrcodeService $qrcodeService): Response
    {
        //récupérer une autre route (url)
        $url = $request->getSchemeAndHttpHost().$this->generateUrl('app_main');


        $qrCode = null;
        $form = $this->createForm(SearchType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $qrCode = $qrcodeService->qrcode($url, 5);
        }

        return $this->render('main/qr.html.twig', [
            'form' => $form->createView(),
            'qrCode' => $qrCode,
        ]);
    }

}
