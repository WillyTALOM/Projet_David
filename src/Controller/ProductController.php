<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/homme', name: 'products')]
    public function productHomme(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('product/homme.html.twig',
         [
            'products' => $products,
        ]);
    }

    #[Route('/femme', name: 'products')]
    public function productfemme(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('product/femme.html.twig',
         [
            'products' => $products,
        ]);
    }

    #[Route('/enfant', name: 'products')]
    public function productenfant(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('product/enfant.html.twig',
         [
            'products' => $products,
        ]);
    }
}
