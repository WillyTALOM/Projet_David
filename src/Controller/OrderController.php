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
            'orderDetails' => $orderDetails,
        ]);
    }
}
