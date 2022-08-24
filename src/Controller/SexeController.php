<?php

namespace App\Controller;

use App\Entity\Sexe;
use App\Form\SexeType;
use App\Repository\SexeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SexeController extends AbstractController
{
    #[Route('/admin/sexe', name: 'admin_sexe')]
    public function index(SexeRepository $sexeRepository): Response
    {
        $sexes = $sexeRepository->findAll();
        return $this->render('sexe/sexeAdmin.html.twig', [
            'sexes' =>  $sexes,
        ]);
    }

    #[Route('/admin/sexe/create', name: 'create_sexe')]
    public function create(Request $request, SexeRepository $sexeRepository, ManagerRegistry $managerRegistry): Response
    {
        $sexe = new Sexe();
        $form = $this->createForm(SexeType::class, $sexe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isvalid()) {
            $sexes = $sexeRepository->findAll();
            $sexeNames = [];

            foreach ($sexes as $sexe) {
                $sexeNames[] = $sexe->getName();
            }


            $manager = $managerRegistry->getManager();
            $manager->persist($sexe);
            $manager->flush();

            return $this->redirectToRoute('admin_sexe');
        }

        return $this->render('sexe/form.html.twig', [
            'sexeForm' => $form->createView()
        ]);
    }

    #[Route('/admin/sexe/update/{id}', name: 'sexe_update')]
    public function update(Sexe $sexe, SexeRepository $sexeRepository, Request $request, ManagerRegistry $managerRegistry): Response
    {
        $form = $this->createForm(
            SexeType::class,
            $sexe
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $sexes =  $sexeRepository->findAll();
            $sexeName = [];

            foreach ($sexes as $sexe) {

                $sexeName[] = $sexe->getName();
            }

            $manager = $managerRegistry->getManager();
            $manager->persist($sexe);
            $manager->flush();
            return $this->redirectToRoute('admin_sexe');
        }

        return $this->render('sexe/form.html.twig', [
            'sexeForm' => $form->createView()
        ]);
    }

    #[Route('/admin/sexe/delete/{id}', name: 'sexe_delete')]
    public function delete(Sexe $sexe, ManagerRegistry $managerRegistry): Response
    {

        $manager = $managerRegistry->getManager();
        $manager->remove($sexe);
        $manager->flush();
        return $this->redirectToRoute('admin_sexe');
    }
}
