<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddressController extends AbstractController
{
    public function createAddress(Request $request, SessionInterface $session, UserInterface $user, ManagerRegistry $managerRegistry)
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repositoryUser = $this->getDoctrine()->getRepository(User::class);

            $user = $repositoryUser->findOneBy(['email' => $user->getUsername()]);

            $address->setAddress($form->get('Address')->getData());
            $address->setAdditional($form->get('Additional')->getData());
            $address->setCity($form->get('city')->getData());
            $address->setCountry($form->get('country')->getData());
            $address->setZip($form->get('zip')->getData());
            $address->setUser($user);


            $manager = $managerRegistry->getManager();
            $manager->persist($address);

            $manager->flush();


            return $this->redirectToRoute('checkout_cart');

        }
        return $this->render('checkout/form-addresse.html.twig', [
            'addressForm' => $form->createView(),
        ]);
    }
}
