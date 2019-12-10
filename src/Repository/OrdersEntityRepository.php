<?php

namespace App\Repository;

use App\Entity\OrdersEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method OrdersEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrdersEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrdersEntity[]    findAll()
 * @method OrdersEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrdersEntity::class);
    }

    // /**
    //  * @return OrdersEntity[] Returns an array of OrdersEntity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrdersEntity
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
