<?php

namespace App\Controller;

use Stripe\StripeClient;
use App\Service\CartService;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'payment')]
    public function index(Request $request, CartService $cartService, ProductRepository $productRepository): Response
    {
        if ($request->headers->get('referer') !== 'htps://127.0.0.1/cart/validation') {
            return $this->redirectToRoute('cart');
        }

        $cartSession = $cartService->getCart();
        $stripeCart = [];

        foreach ($cartSession as $id => $quantity) {
            $product = $productRepository->find($id);
            $stripeElement = [
                'name' => $product->getName(),
                'quantity' => $quantity,
                'amount' => $product->getPrice() * 100,
                'currency' => 'EUR'
            ];
            $stripeCart[] = $stripeElement;
        }

        $stripe = new StripeClient($this->getParameter('stripe_secret_key'));

        $stripeSession = $stripe->checkout->session->create([
            'line_elements' => $stripeCart,
            'mode' => 'payment',
            'success_url' => 'htps://127.0.0.1:8000/payment/success',
            'cancel_url' => 'htps://127.0.0.1:8000/payment/cancel',
            'payment_method_types' => ['card']
        ]);


        return $this->render('payment/index.html.twig', [
            'sessionId' => $stripeSession->id,
        ]);
    }
}
