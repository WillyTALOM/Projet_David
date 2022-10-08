<?php

namespace App\Repository;

use App\Entity\Sexe;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function LastSixtheen(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id ', 'DESC')
            ->setMaxResults(16)
            ->getQuery()
            ->getResult();
    }


    /**
     * @return Product[] Returns an array of Product objects
     */


    public function findAllSexe(Sexe $sexe): array
    {

        return $this->createQueryBuilder('p')
            ->where('sexe MEMBER OF p.sexe')
            ->setParameter('sexe', $sexe)
            ->getQuery()
            ->getResult();
    }


    /**
     * @return Product[] Returns an array of Product objects
     */



    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
