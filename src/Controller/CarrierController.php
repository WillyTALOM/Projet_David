<?php

namespace App\Controller;

use App\Entity\Carrier;
use App\Form\CarrierType;
use App\Repository\CarrierRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CarrierController extends AbstractController
{
    #[Route('/admin/carriers', name: 'admin_carriers')]
    public function index(CarrierRepository $carrierRepository): Response
    {
        return $this->render('carrier/adminList.html.twig', [
            'carriers' => $carrierRepository->findAll()
        ]);
    }

    #[Route('/admin/carrier/create', name: 'carrier_create')]
    public function create(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $carrier = new Carrier();
        $carrierForm = $this->createForm(CarrierType::class, $carrier);
        $carrierForm->handleRequest($request);

        if ($carrierForm->isSubmitted() && $carrierForm->isValid()) {

            $img = $carrierForm['img']->getData();
            $imgName = time() . '-1.' . $img->guessExtension();
            $img->move($this->getParameter('carrier_image_dir'), $imgName);
            $carrier->setImg($imgName);

            $managerRegistry->getManager()->persist($carrier);
            $managerRegistry->getManager()->flush();

            $this->addFlash('success', 'Le transporteur a bien été créé');
            return $this->redirectToRoute('admin_carriers');
        }

        return $this->render('carrier/form.html.twig', [
            'carrierForm' => $carrierForm->createView()
        ]);
    }

    #[Route('/admin/carrier/update/{carrier}', name: 'carrier_update')]
    public function update(Carrier $carrier, Request $request, ManagerRegistry $managerRegistry): Response
    {
        $carrierForm = $this->createForm(CarrierType::class, $carrier);
        $carrierForm->handleRequest($request);

        if ($carrierForm->isSubmitted() && $carrierForm->isValid()) {

            $img = $carrierForm['img']->getData();
            if ($img !== null) {
                $oldImagePath = $this->getParameter('carrier_image_dir') . '/' . $carrier->getImg();
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                $newImageName = time() . '-1.' . $img->guessExtension();
                $img->move($this->getParameter('carrier_image_dir'), $newImageName);
                $carrier->setImg($newImageName);
            }

            $managerRegistry->getManager()->persist($carrier);
            $managerRegistry->getManager()->flush();

            $this->addFlash('success', 'Le transporteur a bien été créé');
            return $this->redirectToRoute('admin_carriers');
        }

        return $this->render('carrier/form.html.twig', [
            'carrierForm' => $carrierForm->createView()
        ]);
    }

    #[Route('/admin/carrier/delete/{carrier}', name: 'carrier_delete')]
    public function delete(Carrier $carrier, ManagerRegistry $managerRegistry): Response
    {
        $imagePath = $this->getParameter('carrier_image_dir') . '/' . $carrier->getImg();
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        $managerRegistry->getManager()->remove($carrier);
        $managerRegistry->getManager()->flush();
        $this->addFlash('success', 'Le transporteur a bien été supprimé');
        return $this->redirectToRoute('admin_carriers');
    }
}
