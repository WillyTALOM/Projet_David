<?php

namespace App\Controller;

use App\Entity\Order;
use Stripe\StripeClient;
use App\Service\CartService;
use Symfony\Component\Mime\Address;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;

class PaymentController extends AbstractController
{
    #[Route('payment/cancel', name: 'payment_cancel')]
    public function cancel(Request $request): Response
    {
        if ($request->headers->get('referer') !== 'https://checkout.stripe.com/') {
            return $this->redirectToRoute('cart');
        }
        return $this->render('payment/cancel.html.twig');
    }

    #[Route('/payment/{order}', name: 'payment')]
    public function index(CartService $cartService, Order $order): Response
    {


        $sessionCart = $cartService->getCart();
        $stripeCart = [];

        foreach ($sessionCart as $line) {
            if ($line['product']->getReduction() > 0) {
                $stripeElement = [ // produit tel que Stripe en a besoin pour le traiter, les noms des index sont importants
                    'quantity' => $line['quantity'],
                    'price_data' => [
                        'currency' => 'EUR',
                        'unit_amount' => $line['product']->getPriceSolde() * 100,
                        'product_data' => [
                            'name' => $line['product']->getName(),
                            // 'description' => $line['product']->getDescription(),
                            // 'images' => [// 'https://127.0.0.1:8000/public/img/product/' . $line['product']->getImage1()]
                        ]
                    ]
                ];
                $stripeCart[] = $stripeElement;
            } else {
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
        }

        $carrier = $order->getCarrier();
        $stripeElement = [
            'quantity' => 1,
            'price_data' => [
                'currency' => 'EUR',
                'unit_amount' => $carrier->getPrice() * 100,
                'product_data' => [
                    'name' => 'Livraison : ' . $carrier->getName(),
                    // 'images' => [
                    //     'https://127.0.0.1:8000/public/img/carrier/' . $carrier->getImg()
                    // ]
                ]
            ]
        ];

        $stripeCart[] = $stripeElement;

        $stripe = new StripeClient($this->getParameter('stripe_secret_key'));

        $stripeSession = $stripe->checkout->sessions->create([
            'line_items' => $stripeCart,
            'mode' => 'payment',
            'success_url' => 'https://127.0.0.1:8000/payment/' . $order->getId() . '/success',
            'cancel_url' => 'https://127.0.0.1:8000/payment/cancel',
            'payment_method_types' => ['card']
        ]);


        return $this->render('payment/index.html.twig', [
            'sessionId' => $stripeSession->id,
            'order' => $order->getId()
        ]);
    }

    #[Route('payment/{order}/success', name: 'payment_success')]
    public function success(
        MailerInterface $mailer,
        Request $request,
        CartService $cartService,
        Order $order,
        ManagerRegistry $managerRegistry
    ): Response {
        if ($request->headers->get('referer') !== 'https://checkout.stripe.com/') {
            return $this->redirectToRoute('cart');
        }

        $cartService->clear();
        $order->setPaid(true);
        $managerRegistry->getManager()->persist($order);
        $managerRegistry->getManager();

        $email = (new TemplatedEmail()) // email pour informer l'admin de la nouvelle commande à expédier
            ->from(new Address($this->container->get('twig')->getGlobals()['contact_email'], 'VDV'))
            ->to(new Address($this->container->get('twig')->getGlobals()['contact_email']))
            ->replyTo(new Address($this->container->get('twig')->getGlobals()['contact_email'], 'VDV'))
            ->subject('VDV - nouvelle commande')
            ->htmlTemplate('email/order_new.html.twig')
            ->context([
                'order' => $order,
                'orderDetails' => $order->getOrderDetails()
            ]);
        $mailer->send($email);

        $email = (new TemplatedEmail()) // email récapitulatif pour le client
            ->from(new Address($this->container->get('twig')->getGlobals()['contact_email'], 'VDV'))
            ->to(new Address($order->getUser()->getEmail(), $order->getUser()->getFirstName() . " " . strtoupper($order->getUser()->getLastName())))
            ->replyTo(new Address($this->container->get('twig')->getGlobals()['contact_email'], 'VDV'))
            ->subject('VDV- récapitulatif de commande')
            ->htmlTemplate('email/order_confirmation.html.twig')
            ->context([
                'order' => $order,
                'orderDetails' => $order->getOrderDetails(),
                'user' => $this->getUser()
            ]);
        $mailer->send($email);

        foreach ($order->getOrderDetails() as $orderDetail) { // gestion des stocks restants en base de données
            $product = $orderDetail->getProductId();
            $product->setQuantity($product->getQuantity() - $orderDetail->getQuantity());
            $managerRegistry->getManager()->persist($product);
        }

        $managerRegistry->getManager()->flush();


        return $this->render('payment/success.html.twig', ['order' => $order,]);
    }
}
