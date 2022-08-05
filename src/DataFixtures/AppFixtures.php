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
        $faker = Factory::create(); /*:: s'appelle opération de resolution de porté*/


        // $product = new Product();
        // $manager->persist($product);

        $homme = new Category();
        $homme->setName('Homme');
        $homme->setSlug('homme');
        $homme->setDescription('Vetements, chaussures, bijoux et accessoires pour hommes');
        $homme->setImg($faker->imageUrl(360, 360, 'animals', true, 'dogs', true, 'jpg'));
        $homme->setUrl('');
        $manager->persist($homme);

        $femme = new Category();
        $femme->setName('Femme');
        $femme->setSlug('femme'); 
        $femme->setDescription('Vetements, chaussures, bijoux et accessoires pour femmes');
        $femme->setImg($faker->imageUrl(360, 360, 'animals', true, 'dogs', true, 'jpg'));  
        $femme->setUrl('');     
        $manager->persist($femme);

        $enfant = new Category();
        $enfant->setName('Enfant');
        $enfant->setSlug('enfant');
        $enfant->setDescription('Vetements, chaussures, bijoux et accessoires pour enfants');
        $enfant->setImg($faker->imageUrl(360, 360, 'animals', true, 'dogs', true, 'jpg'));
        $enfant->setUrl('');
        $manager->persist($enfant);

        $manager->flush();

    
        $categories = $manager->getRepository(Category::class)->findAll();
        // demander au manager daller cherhcher le repo associer a l'entity' category // on recupere categories en base de donnèes

        $slugger = new AsciiSlugger();

        
     
        $chaussure = new Product();
        $chaussure->setName('Basket ADIDAS');
        $chaussure->setSlug(strtolower($slugger->slug(($chaussure->getName()))));
        $chaussure->setAbstract($faker->text(100));
        $chaussure->setDescription($faker->text(1000));
        $chaussure->setQuantity($faker->numberBetween(0,100));
        $chaussure->setPrice($faker->randomFloat(2,4,200));
        $chaussure->setPriceSolde($faker->randomFloat(2,5,210));
        $chaussure->setReduction($faker->numberBetween(0,100));
        $chaussure->setCreatedAt(new \DateTimeImmutable());
        $chaussure->setCategory($homme); 

        $manager->persist($chaussure);

        $manager->flush();
       

        
        $image = new Image();
        $image->setName($faker->imageUrl(360, 360, 'animals', true, 'dogs', true, 'jpg'));
        $image->setProduct($chaussure);
        $chaussure->addImage($image);

        $manager->persist($image);

        $manager->flush();

        $chemise = new Product();
        $chemise->setName('Basket NIKE');
        $chemise->setSlug(strtolower($slugger->slug(($chemise->getName()))));
        $chemise->setAbstract($faker->text(100));
        $chemise->setDescription($faker->text(1000));
        $chemise->setQuantity($faker->numberBetween(0,100));
        $chemise->setPrice($faker->randomFloat(2,4,200));
        $chemise->setPriceSolde($faker->randomFloat(2,5,210));
        $chemise->setReduction($faker->numberBetween(0,100));
        $chemise->setCreatedAt(new \DateTimeImmutable());
        $chemise->setCategory($femme);
        $chemise->setCategory($homme);

        $manager->persist($chemise);

        $manager->flush();
       

        
        $image = new Image();
        $image->setName($faker->imageUrl(360, 360, 'animals', true, 'dogs', true, 'jpg'));
        $image->setProduct($chemise);
        $chemise->addImage($image);

        $manager->persist($image);

        $manager->flush();
    

    }
}
