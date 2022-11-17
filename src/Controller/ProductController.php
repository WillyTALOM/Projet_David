<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\SexeRepository;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
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
            'products' => $produits,


        ]);
    }

    #[Route('/admin/products', name: 'admin_products')]
    public function adminList(ProductRepository $productRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $products = $productRepository->findAll();

        $data = $products;

        $products = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('product/adminList.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/admin/product/create', name: 'create_product')]
    public function create(Request $request, ProductRepository $productRepository, ManagerRegistry $managerRegistry,): Response
    {
        $slugger = new AsciiSlugger();
        $product = new Product();
        $manager = $managerRegistry->getManager();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $products = $productRepository->findAll();

            $productReference = [];
            foreach ($products as $newproduct) {
                $productNames[] = $newproduct->getName();
                $productReference[] = $newproduct->getReference();
            }

            if (in_array($form['reference']->getData(), $productReference)) {
                $this->addFlash('danger', 'Le produit n\'a pas pu être créé : Ce produit existe déja');
                return $this->redirectToRoute('admin_products');
            }

            $infoImage1 = $form['image1']->getData();

            if (empty($infoImage1)) {
                $this->addFlash('danger', 'Le produit n\'a pas pu être créé : l\'image principale est obligatoire mais n\'a pas été renseignée');
                return $this->redirectToRoute('admin_products');
            }

            $extensionImage1 = $infoImage1->guessExtension();
            $nomImage1 = time() . '-1.' . $extensionImage1;
            $infoImage1->move($this->getParameter('product_image_dir'), $nomImage1);
            $product->setImage1($nomImage1);

            $infoImage2 = $form['image2']->getData();
            if ($infoImage2 !== null) {
                $extensionImage2 = $infoImage2->guessExtension();
                $nomImage2 = time() . '-2.' . $extensionImage2;
                $infoImage2->move($this->getParameter('product_image_dir'), $nomImage2);
                $product->setImage2($nomImage2);
            }

            $infoImage3 = $form['image3']->getData();
            if ($infoImage3 !== null) {
                $extensionImage3 = $infoImage3->guessExtension();
                $nomImage3 = time() . '-3.' . $extensionImage3;
                $infoImage3->move($this->getParameter('product_image_dir'), $nomImage3);
                $product->setImage3($nomImage3);
            }

            $product->setCreatedAt(new DateTimeImmutable());
            $product->setPriceSolde($product->getPrice() * (1 - ($product->getReduction() / 100)));
            $product->setSlug(strtolower($slugger->slug($form['name']->getData())));
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
            $productReference = [];
            foreach ($products as $newProduct) {
                $productNames[] = $newProduct->getName();
                $productReference[] = $newProduct->getReference();
            }



            $infoImage1 = $form['image1']->getData();
            if ($infoImage1 !== null) {
                $oldImage1Name = $product->getImage1();
                $oldImage1Path = $this->getParameter('product_image_dir') . '/' . $oldImage1Name;
                if (file_exists($oldImage1Path)) {
                    unlink($oldImage1Path);
                }
                $extensionImage1 = $infoImage1->guessExtension();
                $nomImage1 = time() . '-1.' . $extensionImage1;
                $infoImage1->move($this->getParameter('product_image_dir'), $nomImage1);
                $product->setImage1($nomImage1);
            }

            $infoImage2 = $form['image2']->getData();
            if ($infoImage2 !== null) {
                $oldImage2Name = $product->getImage2();
                if ($oldImage2Name !== null) {
                    $oldImage2Path = $this->getParameter('product_image_dir') . '/' . $oldImage2Name;
                    if (file_exists($oldImage1Path)) {
                        unlink($oldImage2Path);
                    }
                }
                $extensionImage2 = $infoImage2->guessExtension();
                $nomImage2 = time() . '-2.' . $extensionImage2;
                $infoImage2->move($this->getParameter('product_image_dir'), $nomImage2);
                $product->setImage2($nomImage2);
            }

            $infoImage3 = $form['image3']->getData();
            if ($infoImage3 !== null) {
                $oldImage3Name = $product->getImage3();
                if ($oldImage3Name !== null) {
                    $oldImage3Path = $this->getParameter('product_image_dir') . '/' . $oldImage3Name;
                    if (file_exists($oldImage1Path)) {
                        unlink($oldImage3Path);
                    }
                }
                $extensionImage3 = $infoImage3->guessExtension();
                $nomImage3 = time() . '-3.' . $extensionImage3;
                $infoImage3->move($this->getParameter('product_image_dir'), $nomImage3);
                $product->setImage3($nomImage3);
            }
            $product->setPriceSolde($product->getPrice() * (1 - ($product->getReduction() / 100)));

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
        $image1path = $this->getParameter('product_image_dir') . '/' . $product->getImage1();
        if (file_exists($image1path)) {
            unlink($image1path);
        }

        if ($product->getImage2() !== null) {
            $image2path = $this->getParameter('product_image_dir') . '/' . $product->getImage2();
            if (file_exists($image2path)) {
                unlink($image2path);
            }
        }

        if ($product->getImage3() !== null) {
            $image3path = $this->getParameter('product_image_dir') . '/' . $product->getImage3();
            if (file_exists($image3path)) {
                unlink($image3path);
            }
        }


        $manager = $managerRegistry->getManager();
        $manager->remove($product);
        $manager->flush();

        $this->addFlash('success', 'Le produit a bein été supprimé');
        return $this->redirectToRoute('admin_products');
    }

    #[Route('/products/{sexe}', name: 'products_by_sexe')]
    public function getProduitsBySexe(string $sexe, SexeRepository $sexeRepository, ProductRepository $productRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $sexe = $sexeRepository->findOneBy(['name' => $sexe]);
        $products = $productRepository->findBy(['sexe' => $sexe]);

        $data = $products;

        $products = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('product/sexe.html.twig', [
            'products' => $products,
            'sexe' => $sexe
        ]);
    }
}
