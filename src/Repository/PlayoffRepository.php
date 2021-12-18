<?php

namespace App\Repository;

use App\Entity\Playoff;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Playoff|null find($id, $lockMode = null, $lockVersion = null)
 * @method Playoff|null findOneBy(array $criteria, array $orderBy = null)
 * @method Playoff[]    findAll()
 * @method Playoff[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayoffRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playoff::class);
    }

    // /**
    //  * @return Playoff[] Returns an array of Playoff objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Playoff
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
