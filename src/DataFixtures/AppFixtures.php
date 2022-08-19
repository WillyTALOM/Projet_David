<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Sexe;
use App\Entity\Brand;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\AsciiSlugger;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $slugger = new AsciiSlugger();
        $faker = Factory::create('fr_FR');
        // $product = new Product();
        // $manager->persist($product);

        $category = new Category(); // crée la nouvelle catégorie
        $category->setName('Ambiance'); // définit le nom de la catégorie
        $category->setSlug('ambiance'); // définit le slug de la catégorie
        $manager->persist($category); // précise au gestionnaire qu'on va vouloir envoyer un obket en base de données (le rend persistant / liste d'attente)

        $category = new Category();
        $category->setName('Stratégie');
        $category->setSlug('strategie');
        $manager->persist($category);

        $category = new Category();
        $category->setName('Junior');
        $category->setSlug('junior');
        $manager->persist($category);

        $category = new Category();
        $category->setName('Classique');
        $category->setSlug('classique');
        $manager->persist($category);

        $manager->flush(); // ce flush est nécessaire pour envoyer les catégories en base de données car on en aura besoin juste après pour alimenter les produits

        $marque = new Brand();
        $marque->setName('Adidas');
        $manager->persist($marque);

        $marque = new Brand();
        $marque->setName('Nike');
        $manager->persist($marque);

        $marque = new Brand();
        $marque->setName('Puma');
        $manager->persist($marque);

        $manager->flush();



        $sexe = new Sexe();
        $sexe->setName('homme');
        $sexe->setSlug(strtolower($slugger->slug($sexe->getName())));
        $manager->persist($sexe);

        $sexe = new Sexe();
        $sexe->setName('femme');
        $sexe->setSlug(strtolower($slugger->slug($sexe->getName())));
        $manager->persist($sexe);

        $sexe = new Sexe();
        $sexe->setName('enfant');
        $sexe->setSlug(strtolower($slugger->slug($sexe->getName())));
        $manager->persist($sexe);
        $manager->flush();









        $categories = $manager->getRepository(Category::class)->findAll(); // récupère les catégories en base de données
        $sexes = $manager->getRepository(Sexe::class)->findAll();
        $marques = $manager->getRepository(Brand::class)->findAll();


        for ($i = 1; $i <= 20; $i++) {
            $product = new Product();
            $product->setName($faker->text(35));
            $product->setSlug(strtolower($slugger->slug($product->getName())));
            $product->setAbstract($faker->text(255));
            $product->setDescription($faker->text(1000));
            $product->setQuantity($faker->numberBetween(0, 100));
            $product->setPrice($faker->randomFloat(2, 4, 200));
            $product->setPriceSolde($faker->randomFloat(2, 4, 200));
            $product->setReduction($faker->randomFloat(2, 4, 200));
            $product->setCreatedAt(new \DateTimeImmutable());

            $indexS = array_rand($sexes, 1);
            $sexe = $sexes[$indexS];

            $indexB = array_rand($marques, 1); // renvoit un index aléatoire du tableau contenant les catégories
            $marque = $marques[$indexB];

            $index = array_rand($categories, 1); // renvoit un index aléatoire du tableau contenant les catégories
            $category = $categories[$index]; // récupère la valeur liée à cet index
            $product->setCategory($category); // définit la catégorie récupérer à la ligne précédente
            $product->setBrand($marque);
            $product->setSexe($sexe);
            $manager->persist($product);
        }

        $manager->flush(); // envoit les objets persistés en base de données
    }
}
