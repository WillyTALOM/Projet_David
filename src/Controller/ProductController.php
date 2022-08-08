<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/homme', name: 'homme')]
    public function productHomme(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('category/homme.html.twig',
         [
            'products' => $products,
        ]);
    }

    #[Route('/homme/{slug}', name: 'homme_show')]
    public function showHomme($slug,ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneBy(['slug'=>$slug]);
        return $this->render('product/homme.html.twig',
         [
            'product' => $product,
        ]);
    }

    #[Route('/femme', name: 'femme')]
    public function productfemme(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('category/femme.html.twig',
         [
            'products' => $products,
        ]);   
    }

    #[Route('/femme/{slug}', name: 'femme_show')]
    public function showFemme($slug,ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneBy(['slug'=>$slug]);
        return $this->render('product/femme.html.twig',
         [
            'product' => $product,
        ]);

    }    

    #[Route('/enfant', name: 'enfant')]
    public function productenfant(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('category/enfant.html.twig',
         [
            'products' => $products,
        ]);
    }

    #[Route('/enfant/{slug}', name: 'enfant_show')]
    public function showEnfant($slug,ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneBy(['slug'=>$slug]);
        return $this->render('product/enfant.html.twig',
         [
            'product' => $product,
        ]);

    }    
}
