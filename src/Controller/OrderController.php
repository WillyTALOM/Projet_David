<?php

namespace App\Controller;


use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\OrderDetailsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/admin/orders', name: 'admin_orders')]
    public function index(OrderRepository $orderRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $orders = $orderRepository->findAll();
        $data = $orders;

        $orders = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('admin/order/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/order/details', name: 'admin_order_details')]
    public function orderDetails(OrderDetailsRepository $orderDetailsRepository): Response
    {
        $orderDetails = $orderDetailsRepository->findAll();

        return $this->render('order/orderDetails.html.twig', [
            'orderDetails' => $orderDetails,
        ]);
    }

    #[Route('/account/{id}/orders', name: 'user_account_orders')]
    public function orders(OrderRepository $orderRepository, PaginatorInterface $paginator, Request $request)
    {
        $orders = $orderRepository->findSuccessOrders($this->getUser());

        $data = $orders;

        $orders = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('account/orders.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/account/orders/{reference}', name: 'user_account_order_details')]
    public function show($reference, OrderRepository $orderRepository, ProductRepository $productRepository)
    {
        $order = $orderRepository->findOneByReference($reference);

        $product = $productRepository->findOneByReference($reference);


        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('user_account_orders');
        }

        return $this->render('account/order_details.html.twig', [
            'product' => $product,
            'order' => $order,


        ]);
    }
}
