<?php

namespace App\Repository;

use App\Entity\Demo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Demo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Demo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Demo[]    findAll()
 * @method Demo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demo::class);
    }

    public function transform(Demo $demo)
    {
        return [
            'id'    => (int) $demo->getId(),
            'title' => (string) $demo->getTitle(),
            'description' => (string) $demo->getDescription(),
            'state' => (int) $demo->getState()
        ];
    }

    public function transformAll()
    {
        $demoEntry = $this->findAll();
        $return = [];

        foreach ($demoEntry as $demoSingle) {
            $return[] = $this->transform($demoSingle);
        }

        return $return;
    }

    // /**
    //  * @return Demo[] Returns an array of Demo objects
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
    public function findOneBySomeField($value): ?Demo
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
