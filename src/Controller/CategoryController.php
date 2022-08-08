<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/homme', name: 'homme')]
    public function homme(CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findAll();
        return $this->render('category/homme.html.twig', [
            'category' => $category,
        ]);
    }

    
    #[Route('/femme', name: 'femme')]
    public function femme(CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findAll();
        return $this->render('category/femme.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/enfant', name: 'enfant')]
    public function enfant(CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findAll();
        return $this->render('category/enfant.html.twig', [
            'category' => $category,
        ]);
    }
}
