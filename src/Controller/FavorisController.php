<?php

namespace App\Controller;

use App\Entity\Favoris;
use App\Entity\Product;
use App\Repository\FavorisRepository;
use App\Repository\ProductRepository;
use
    Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\FavorisService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavorisController extends AbstractController
{
    #[Route('/favoris', name: 'favoris')]
    public function index(ProductRepository $productRepository, FavorisService $favorisService, FavorisRepository $favorisRepository): Response
    {
        return $this->render('favoris/index.html.twig', [
            'favories' => $favorisService->getFavoris(),
            'total' => $favorisService->getTotal(),
            'favoris' => $favorisRepository->findAll(),
            'products' => $productRepository->findAll()
        ]);
    }


    #[ROUTE('/favoris/add/{id}', name: 'favoris_add')]
    public function add(FavorisService $favorisService, int $id, ManagerRegistry $managerRegistry, Product $product): Response
    {
        $favorisService->add($id);
        $this->addFlash('success', 'L\'article a bien été ajouté dans vos favoris');

        $favoris = new Favoris();


        $favoris->addUser($this->getUser());
        $favoris->addProduct($product);

        $manager = $managerRegistry->getManager();
        $manager->persist($favoris);


        $manager->flush();

        return $this->redirectToRoute('favoris');
    }


    #[Route('favoris/remove/{id}', name: 'favoris_remove')]
    public function remove(FavorisService $favorisService, int $id): Response
    {
        $favorisService->remove($id);
        $this->addFlash('success', 'L\'article a bien été supprimé de vos favoris');
        return $this->redirectToRoute('favoris');
    }

    #[Route('favoris/delete/{id}', name: 'favoris_delete')]
    public function delete(FavorisService $favorisService, int $id): Response
    {
        $favorisService->delete($id);
        $this->addFlash('success', 'L\'article a bien été supprimé de vos favoris');
        return $this->redirectToRoute('favoris');
    }

    #[Route('favoris/clear', name: 'favoris_clear')]
    public function clear(FavorisService $favorisSevice): Response
    {
        $favorisSevice->clear();
        $this->addFlash('success', 'Votre panier favoris a bien été vidé');
        return $this->redirectToRoute('favoris');
    }

    public function getNbFavoris(FavorisService $favorisService): Response
    {
        return $this->render('favoris/nbFavoris.html.twig', ['nbFavoris' => $favorisService->getNbFavoris()]);
    }
}
