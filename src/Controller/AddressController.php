<?php

namespace App\Controller;

use App\Entity\User;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddressController extends AbstractController
{
    #[Route('/account/addresses/{id}', name: 'user_account_address')]
    public function index(AddressRepository $addressRepository): Response
    {
        $addresses = $addressRepository->findAll();
        return $this->render('account/address.html.twig', [
            "addresses" => $addresses
        ]);
    }

    #[Route('/account/{id}/ajouter-une-adresse', name: 'user_account_address_add')]
    public function addAddress(Request $request, AddressRepository $addressRepository, ManagerRegistry $managerRegistry): Response
    {
        $address = new Address();

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addresses = $addressRepository->findAll();
            $newAddress = [];
            $addressAdditional = [];
            $addressZip = [];
            $addressCity = [];
            $addressCountry = [];

            foreach ($addresses as $existingAddress) {
                $newAddress[] = $existingAddress->getAddress();
                $addressAdditional[] = $existingAddress->getAdditional();
                $addressZip[] = $existingAddress->getZip();
                $addressCity[] = $existingAddress->getCity();
                $addressCountry[] = $existingAddress->getCity();
            }

            $address->setUser($this->getUser());

            $manager = $managerRegistry->getManager();
            $manager->persist($address);
            $manager->flush();

            $this->addFlash('success', 'L\'Adresse a bien été créé');
            return $this->redirectToRoute('user_account_address');
        }

        return $this->render('account/address_form.html.twig', [
            'addressform' => $form->createView()
        ]);
    }


    #[Route('/account/modifier-une-adresse/{id}', name: 'user_account_address_update')]
    public function addressUpdate(Request $request,  Address $address, AddressRepository $addressRepository, ManagerRegistry $managerRegistry): Response
    {
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if (!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('user_account_address');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $addresses = $addressRepository->findAll();
            $newAddress = [];
            $addressAdditional = [];
            $addressZip = [];
            $addressCity = [];
            $addressCountry = [];

            foreach ($addresses as $existingAddress) {
                $newAddress[] = $existingAddress->getAddress();
                $addressAdditional[] = $existingAddress->getAdditional();
                $addressZip[] = $existingAddress->getZip();
                $addressCity[] = $existingAddress->getCity();
                $addressCountry[] = $existingAddress->getCity();
            }

            $address->setUser($this->getUser());

            $manager = $managerRegistry->getManager();
            $manager->persist($address);
            $manager->flush();

            $this->addFlash('success', 'L\'Adresse a bien été modifier');
            return $this->redirectToRoute('user_account_address');
        }


        return $this->render('account/address_form.html.twig', [
            'addressform' => $form->createView()
        ]);
    }


    #[Route('/account/supprimer-une-adresse/{id}', name: 'user_account_address_delete')]
    public function addressDelete(Address $address, ManagerRegistry $managerRegistry): Response
    {
        $manager = $managerRegistry->getManager();
        $manager->remove($address);
        $manager->flush();

        $this->addFlash('success', 'L\'Adresse a bein été supprimé');
        return $this->redirectToRoute('user_account_address');
    }
}