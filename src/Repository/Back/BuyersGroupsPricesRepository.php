<?php

namespace App\Repository\Back;

use App\Entity\Back\BuyersGroupsPrices;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BuyersGroupsPrices|null find($id, $lockMode = null, $lockVersion = null)
 * @method BuyersGroupsPrices|null findOneBy(array $criteria, array $orderBy = null)
 * @method BuyersGroupsPrices[]    findAll()
 * @method BuyersGroupsPrices[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuyersGroupsPricesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BuyersGroupsPrices::class);
    }

    // /**
    //  * @return BuyersGroupsPrices[] Returns an array of BuyersGroupsPrices objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BuyersGroupsPrices
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
