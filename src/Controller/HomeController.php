<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ProductRepository $productRepository): Response
    {
        $produits = $productRepository->findAll();
        return $this->render('home/index.html.twig', [
            'products' => $produits,
        ]);
    }

    #[Route('/home/{slug}', name: 'home_show')]
    public function showHome($slug,ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneBy(['slug'=>$slug]);
        return $this->render('product/femme.html.twig'/*j'ai laissé product/femme.html.twig ppour l'exemple*/ ,
         [
            'product' => $product,
        ]);

    }    
}
