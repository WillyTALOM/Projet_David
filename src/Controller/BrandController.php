<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Entity\Category;
use App\Repository\BrandRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BrandController extends AbstractController
{
    #[Route('/admin/brand', name: 'admin_brand')]
    public function index(BrandRepository $brandRepository): Response
    {
        $brands = $brandRepository->findAll(); 
        return $this->render('brand/brandAdmin.html.twig', [
            'brands' =>  $brands,
        ]);
    }

        
    #[Route('/admin/brand/create', name: 'create_brand')]
    public function create(Request $request, BrandRepository $brandRepository, ManagerRegistry $managerRegistry): Response
    {
        $brand = new Brand();
        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isvalid())
        {
            $brands = $brandRepository->findAll();
            $brandName = [];

            foreach ($brands as $bran){
                $brandName [] = $bran->getName();
            }

            $manager = $managerRegistry->getManager();
            $manager->persist($brand);
            $manager->flush();

            $this->addFlash('success', 'La marque a bien été créé'); 
            return $this->redirectToRoute('admin_brand');

        }

    
        return $this->render('brand/form.html.twig', [
            'brandForm' => $form->createView()
        ]);

    }

    #[Route('/admin/brand/update/{id}', name: 'update_brand')]
    public function update(Brand $brand, Request $request, BrandRepository $brandRepository, ManagerRegistry $managerRegistry): Response
    {
        $form = $this->createForm(BrandType::class,
        $brand);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $brands =  $brandRepository->findAll();
            $brandName = [];

            foreach ($brands as $brand){

                $brandName[] = $brand->getName();
            }



            $manager = $managerRegistry->getManager();
            $manager->persist($brand);
            $manager->flush();

            $this->addFlash('success', 'La marque a bien été modifié');
            return $this->redirectToRoute('admin_brand');
            }
    
        

        return $this->render('brand/form.html.twig', [
            'brandForm' => $form->createView()
        ]);
    } 
    
    #[Route('/admin/brand/delete/{id}', name: 'delete_brand')]
    public function delete(Brand $brand , ManagerRegistry $managerRegistry): Response
    {
       
        $manager = $managerRegistry->getManager();
        $manager->remove($brand);
        $manager->flush();
        $this->addFlash('success', 'La marque a bein été supprimé');
        return $this->redirectToRoute
        ('admin_brand');

    }
}
