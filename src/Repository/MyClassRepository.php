<?php

namespace App\Repository;

use App\Entity\CountryCodes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CountryCodes|null find($id, $lockMode = null, $lockVersion = null)
 * @method CountryCodes|null findOneBy(array $criteria, array $orderBy = null)
 * @method CountryCodes[]    findAll()
 * @method CountryCodes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MyClassRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CountryCodes::class);
    }

    // /**
    //  * @return CountryCodes[] Returns an array of CountryCodes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CountryCodes
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
