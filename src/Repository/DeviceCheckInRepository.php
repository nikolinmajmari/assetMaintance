<?php

namespace App\Repository;

use App\Entity\DeviceCheckIn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DeviceCheckIn|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeviceCheckIn|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeviceCheckIn[]    findAll()
 * @method DeviceCheckIn[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviceCheckInRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeviceCheckIn::class);
    }

    // /**
    //  * @return DeviceCheckIn[] Returns an array of DeviceCheckIn objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DeviceCheckIn
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
