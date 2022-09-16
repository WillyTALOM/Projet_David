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
}
