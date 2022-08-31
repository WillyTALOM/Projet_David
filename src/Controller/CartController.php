<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cartService->getCart(),
            'total' => $cartService->getTotal()
        ]);
    }

    #[ROUTE('/cart/add/{id}', name: 'cart_add')]
    public function add(CartService $cartService, int $id, Request $request): Response
    {
        $cartService->add($id);
        $this->addFlash('success', 'L\'article a bien été ajouté dans votre panier');
        if ($request->headers->get('referer') === 'https://127.0.0.1:8000/cart') {
            return $this->redirectToRoute('cart');
        }

        return $this->redirectToRoute('cart');
    }

    #[Route('cart/remove/{id}', name: 'cart_remove')]
    public function remove(CartService $cartService, int $id): Response
    {
        $cartService->remove($id);
        $this->addFlash('success', 'L\'article a bien été supprimé de votre panier');
        return $this->redirectToRoute('cart');
    }

    #[Route('cart/delete/{id}', name: 'cart_delete')]
    public function delete(CartService $cartService, int $id): Response
    {
        $cartService->delete($id);
        $this->addFlash('success', 'L\'article a bien été supprimé de votre panier');
        return $this->redirectToRoute('cart');
    }

    #[Route('cart/clear', name: 'cart_clear')]
    public function clear(CartService $cartSevice): Response
    {
        $cartSevice->clear();
        $this->addFlash('success', 'Votre panier a bien été vidé');
        return $this->redirectToRoute('cart');
    }

    public function getNbProducts(CartService $cartService): Response
    {
        return $this->render('cart/nbProducts.html.twig', ['nbProducts' => $cartService->getNbProducts()]);
    }


    #[Route('/cart/validation', name: 'cart_validation')]
    public function validate(CartService $cartService): Response
    {
        // formulaire ppur gerer les info (adresse de livraison et facturation)
        return $this->render('cart/validation.html.twig', [
            'cart' => $cartService->getCart(),
            'total' => $cartService->getTotal()

        ]);
    }
}
