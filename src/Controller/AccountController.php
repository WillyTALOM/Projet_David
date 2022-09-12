<?php

namespace App\Controller;

use App\Repository\AddressRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'user_account')]
    public function index(OrderRepository $orderRepository, AddressRepository $addressRepository): Response
    {
        $addresses = $addressRepository->findAll($this->getUser());
        $orders = $orderRepository->findSuccessOrders($this->getUser());
        return $this->render('account/index.html.twig', [
            'orders' => $orders,
            'addresses' => $addresses
        ]);
    }

    #[Route('/account/orders', name: 'user_account_orders')]
    public function orders(OrderRepository $orderRepository)
    {
        $orders = $orderRepository->findSuccessOrders($this->getUser());

        return $this->render('account/order.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/account/orders/{reference}', name: 'user_account_order_details')]
    public function show($reference, OrderRepository $orderRepository)
    {
        $order = $orderRepository->findOneByReference($reference);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_order');
        }

        return $this->render('account/order_show.html.twig', [
            'order' => $order
        ]);
    }
}
