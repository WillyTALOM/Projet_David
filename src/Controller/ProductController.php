<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\SexeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{


    #[Route('/product/{slug}', name: 'product_show')]
    public function show($slug, ProductRepository $productRepository): Response
    {
        $produits = $productRepository->findAll();
        $product = $productRepository->findOneBy(['slug' => $slug]);
        return $this->render('product/productShow.html.twig', [
            'product' => $product,
            'products' => $produits
        ]);
    }

    #[Route('/admin/products', name: 'admin_products')]
    public function adminList(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('product/adminList.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/admin/product/create', name: 'create_product')]
    public function create(Request $request, ProductRepository $productRepository, ManagerRegistry $managerRegistry): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $products = $productRepository->findAll();
            $productNames = [];
            foreach ($products as $product) {
                $productNames[] = $product->getName();
            }

            if (in_array($form['name']->getData(), $productNames)) {
                $this->addFlash('danger', 'Le produit n\'a pas pu être créé : le nom de produit est déjà utilisé');
                return $this->redirectToRoute('admin_products');
            }

            $slugger = new AsciiSlugger();
            $product->setSlug(strtolower($slugger->slug($form['name']->getData())));
            $product->setCreatedAt(new DateTimeImmutable());

            $manager = $managerRegistry->getManager();
            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', 'Le produit a bien été créé');
            return $this->redirectToRoute('admin_products');
        }

        return $this->render('product/form.html.twig', [
            'productForm' => $form->createView()
        ]);
    }

    #[Route('/admin/product/update/{id}', name: 'update_product')]
    public function update(Request $request, ProductRepository $productRepository, ManagerRegistry $managerRegistry, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $products = $productRepository->findAll();
            $productNames = [];
            foreach ($products as $product) {
                $productNames[] = $product->getName();
            }

            if (in_array($form['name']->getData(), $productNames)) {
                $this->addFlash('danger', 'Le produit n\'a pas pu être modifié : le nom de produit est déjà utilisé');
                return $this->redirectToRoute('admin_products');
            }

            $slugger = new AsciiSlugger();
            $product->setSlug(strtolower($slugger->slug($form['name']->getData())));
            $manager = $managerRegistry->getManager();
            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', 'Le produit a bien été modifié');
            return $this->redirectToRoute('admin_products');
        }

        return $this->render('product/form.html.twig', [
            'productForm' => $form->createView()
        ]);
    }

    #[Route('/admin/product/delete/{id}', name: 'delete_product')]
    public function delete(Product $product, ManagerRegistry $managerRegistry): Response
    {

        $manager = $managerRegistry->getManager();
        $manager->remove($product);
        $manager->flush();

        $this->addFlash('success', 'Le produit a bein été supprimé');
        return $this->redirectToRoute('admin_products');
    }

    #[Route('/products/{sexe}', name: 'products_by_sexe')]
    public function getProduitsPourHomme(string $sexe, SexeRepository $sexeRepository, ProductRepository $productRepository): Response
    {
        $sexe = $sexeRepository->findOneBy(['name' => $sexe]);
        return $this->render('product/sexe.html.twig', [
            'products' => $productRepository->findBy(['sexe' => $sexe])
        ]);
    }
}
