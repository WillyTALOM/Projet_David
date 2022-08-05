<?php

namespace App\DataFixtures;

use App\Entity\BrandCategory;
use Faker\Factory;
use App\Entity\Image;
use DateTimeImmutable;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\ProductCategory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\AsciiSlugger;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = $manager->getRepository(Category::class)->findAll();
        $slugger = new AsciiSlugger();
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++){
        $category = new Category();
        $category->setName($faker->name(5));
        $category->setSlug(strtolower($slugger->slug(($category->getName()))));
        $category->setUrl($faker->imageUrl());
        $manager->persist($category);
        
        }
        
        $manager->flush();


        for ($i = 0; $i < 5; $i++){
            $productCategory = new ProductCategory();
            $productCategory->setBags($faker->text(5));
            $productCategory->setShoes($faker->text(5));
            $productCategory->setClothe($faker->text(5));
            $productCategory->setWatch($faker->text(5));
            $productCategory->setAccessory($faker->text(5));
            $productCategory->addCategory($category);
            

            $manager->persist($productCategory);
        
            $manager->flush();
        }


        for ($i = 0; $i < 5; $i++){
            $brandCategory = new BrandCategory();
            $brandCategory->setTrendyBrands($faker->text(5));
            $brandCategory->setLuxuryBrands($faker->text(5));
            $brandCategory->addCategory($category);
            $brandCategory->addProductCategory($productCategory);
            $manager->persist($brandCategory);
        
            $manager->flush();

        }
        
      
        for ($i = 0; $i < 20; $i++){
        $product = new Product();
        $product->setName($faker->text(10));
        $product->setSlug(strtolower($slugger->slug(($product->getName()))));
        $product->setAbstract($faker->text(100));
        $product->setDescription($faker->text(1000));
        $product->setQuantity($faker->numberBetween(0,80));
        $product->setPrice($faker->randomFloat(2,4,200));
        $product->setPriceSolde($faker->randomFloat(2,5,210));
        $product->setReduction($faker->numberBetween(0,100));
        $product->setCreatedAt(new \DateTimeImmutable()); 
        $product->setCategory($category);
        $product->setProductCategory($productCategory);
        $product->addBrandCategory($brandCategory);
        

        $manager->persist($product);
        
        $manager->flush();
        }

       

        
        
    }
}
