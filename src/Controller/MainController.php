<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function generate(Pdf $pdf) :PdfResponse
    {
//        Generation d'un PDF à partir d'un template HTML : Le fichier PDF est directement téléchargé sur le PC
        $nom = "FARLAN";
        $prenom = "François";
        $societe = "Google";

        $html = $this->renderView('main/template.html.twig', [
            'nom'  => $nom,
            'prenom' => $prenom,
            'societe' =>$societe,
        ]);

        return new PdfResponse(
            $pdf->getOutputFromHtml($html),
            'file1.pdf'
        );


    }

}
