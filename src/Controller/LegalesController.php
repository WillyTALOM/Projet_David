<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MentionsLégalesController extends AbstractController
{
    #[Route('/legales/CGU', name: 'app_legales')]
    public function indexCGU(): Response
    {
        return $this->render('legales/CGU.html.twig', [
            'controller_name' => 'LegalesController',
        ]);
    }

    #[Route('/legales/CGV', name: 'app_legales')]
    public function indexCGV(): Response
    {
        return $this->render('legales/CGV.html.twig', [
            'controller_name' => 'LegalesController',
        ]);
    }

    #[Route('/legales/mentionslegales', name: 'app_legales')]
    public function indexmentionslegales(): Response
    {
        return $this->render('legales/mentionslegales.html.twig', [
            'controller_name' => 'LegalesController',
        ]);
    }
}
