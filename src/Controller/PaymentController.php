<?php

namespace App\Controller;

use App\Entity\Order;
use Stripe\StripeClient;
use App\Service\CartService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'payment')]
    public function index(Request $request, CartService $cartService): Response
    {
        if ($request->headers->get('referer') !== 'https://127.0.0.1:8000/cart/validation') {
            return $this->redirectToRoute('cart');
        }

        $sessionCart = $cartService->getCart();
        $stripeCart = [];

        foreach ($sessionCart as $line) {
            $stripeElement = [ // produit tel que Stripe en a besoin pour le traiter, les noms des index sont importants
                'quantity' => $line['quantity'],
                'price_data' => [
                    'currency' => 'EUR',
                    'unit_amount' => $line['product']->getPrice() * 100,
                    'product_data' => [
                        'name' => $line['product']->getName(),
                        // 'description' => $line['product']->getDescription(),
                        // 'images' => [// 'https://127.0.0.1:8000/public/img/product/' . $line['product']->getImg1()]
                    ]
                ]
            ];
            $stripeCart[] = $stripeElement;
        }

        $stripe = new StripeClient($this->getParameter('stripe_secret_key'));

        $stripeSession = $stripe->checkout->sessions->create([
            'line_items' => $stripeCart,
            'mode' => 'payment',
            'success_url' => 'https://127.0.0.1:8000/payment/success',
            'cancel_url' => 'https://127.0.0.1:8000/payment/cancel',
            'payment_method_types' => ['card']
        ]);


        return $this->render('payment/index.html.twig', [
            'sessionId' => $stripeSession->id,
            // 'order' => $order->getId()
        ]);
    }

    #[Route('payment/success', name: 'payment_success')]
    public function success(Request $request, CartService $cartService): Response
    {
        if ($request->headers->get('referer') !== 'https://checkout.stripe.com/') {
            return $this->redirectToRoute('cart');
        }

        $cartService->clear();

        return $this->render('payment/success.html.twig');
    }


    #[Route('payment/cancel', name: 'payment_cancel')]
    public function cancel(Request $request): Response
    {
        if ($request->headers->get('referer') !== 'https://checkout.stripe.com/') {
            return $this->redirectToRoute('cart');
        }
        return $this->render('payment/cancel.html.twig');
    }
}
