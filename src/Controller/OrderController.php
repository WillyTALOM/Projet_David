<?php

namespace App\Controller;

use App\Repository\OrderDetailsRepository;
use App\Repository\OrderRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/admin/orders', name: 'admin_orders')]
    public function index(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findAll();

        return $this->render('order/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/order/details', name: 'admin_orders_details')]
    public function orderDetails(OrderDetailsRepository $orderDetailsRepository): Response
    {
        $orderDetails = $orderDetailsRepository->findAll();

        return $this->render('order/index.html.twig', [
            'orderDetailss' => $orderDetails,
        ]);
    }

    #[Route('/orders', name: 'orders')]
    public function orders(OrderRepository $orderRepository, CartService $cartService): Response
    {
        $user = $this->getUser();
        $cartProducts = $cartService->getCart();

        if (empty($cartProducts['products'])) {
            return $this->redirectToRoute('products');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $user     //Permet de passer l'utilisateur courant dans le tableau d'options du OrderType
        ]);


        return $this->renderForm('order/index.html.twig', [
            'form' => $form,
            'cart' => $cartProducts,
            'totalPrice' => $cartProducts['totals']['price']
        ]);
    }
}
