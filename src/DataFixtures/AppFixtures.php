<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Image;
use DateTimeImmutable;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\AsciiSlugger;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $category = new Category();
        $category->setName('Ambiance');
        $category->setSlug('ambiance');
        $manager->persist($category);

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

        $manager->flush();

        

        $faker = Factory::create(); /*:: s'appelle opération de resolution de porté*/

        $categories = $manager->getRepository(Category::class)->findAll();
        // demander au manager daller cherhcher le repo associer a l'entity' category // on recupere categories en base de donnèes

        $slugger = new AsciiSlugger();

        
    for ($i = 0; $i < 20; $i++) { 
        $product = new Product();
        $product->setName($faker->text(35));
        $product->setSlug(strtolower($slugger->slug(($product->getName()))));
        $product->setAbstract($faker->text(100));
        $product->setDescription($faker->text(1000));
        $product->setQuantity($faker->numberBetween(0,100));
        $product->setPrice($faker->randomFloat(2,4,200));
        $product->setCreatedAt(new \DateTimeImmutable());



        $index = array_rand($categories, 1); // renvoit un index aléatoire du tableau contenant les catégories
        $category = $categories[$index]; // récupère la valeur liée à cet index
        $product->setCategory($category); // définit la catégorie récupérer à la ligne précédente

        $manager->persist($product);

        $manager->flush();
       

        
   }
        
        $image = new Image();
        $image->setName($faker->imageUrl(360, 360, 'animals', true, 'dogs', true, 'jpg'));
        $image->setProduct($product);

        $manager->persist($image);

        $manager->flush();
    

    }
}
