<?php

namespace App\Controller;

use App\Entity\Sexe;
use App\Repository\ProductRepository;
use App\Repository\SexeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SexeController extends AbstractController
{
    #[Route('/{slug}', name: 'sexe')]
    public function index($slug, SexeRepository $sexeRepository, ProductRepository $productRepository): Response
    {
        $sexe = $sexeRepository->findAll();
        $produits = $productRepository->findAll();
        return $this->render('sexe/index.html.twig', [
            'sexe' => $sexe,
            'products' => $produits
        ]);
    }
}
