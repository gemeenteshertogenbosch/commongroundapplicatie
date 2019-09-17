<?php

namespace App\Repository;

use App\Entity\GithubConnect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GithubConnect|null find($id, $lockMode = null, $lockVersion = null)
 * @method GithubConnect|null findOneBy(array $criteria, array $orderBy = null)
 * @method GithubConnect[]    findAll()
 * @method GithubConnect[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GithubConnectRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GithubConnect::class);
    }

    // /**
    //  * @return GithubConnect[] Returns an array of GithubConnect objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GithubConnect
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
